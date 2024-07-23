<?php

namespace App\Middleware;

use App\Entity\User;
use App\Core\Request;
use App\Core\Session;
use App\Services\AuthService;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class AuthoriseMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly Session $session,
        private readonly Request $requestService,
        private readonly AuthService $authService,
        private readonly ResponseFactoryInterface $responseFactory
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (is_null($this->session->get('user'))) {
            if ($request->getMethod() === 'GET' && !$this->requestService->isXhr($request)) {
                $this->session->put('_redirect', (string) $request->getUri());
                return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Location', '/login');
            }
        } else {
            // Get the user by id
            $user = $this->entityManager->getRepository(User::class)
                        ->find($this->session->get('user'));
            
            if ($user) {

                // Session Validation
                $metaData = [
                    'sessionId' => $this->session->getId(),
                    'sessionToken' => $this->session->getCookie('session_token'),
                    'ipAddress' => $this->requestService->getIpAddress($request),
                    'userAgent' => $this->requestService->getUserAgent($request),
                ];

                $session = $this->authService->getUserSession($metaData);

                if (!$session) {
                    // dd('Session hijacking detected');
                    $this->authService->logout();
                    return $this->responseFactory
                        ->createResponse(302)
                        ->withHeader('Location', '/login');
                }

                $last_activity = $session->getLastActivity()->format('Y-m-d H:i:s');

                // Calculate the next token refresh time
                $refresh_interval = 5 * 60; // 5 minutes in seconds
                $token_refresh_time = strtotime($last_activity) + $refresh_interval;

                // Check if it's time to refresh the session token
                if (time() > $token_refresh_time) {
                    $this->authService->regenerateSessionToken($session);
                    $this->authService->updateLastActivity($session);
                }
                
                // Allow the user to access the application
                $role = $user->getUsername() === 'admin' ? 'admin' : 'user';
                $request = $request->withAttribute('role', $role);
                $request = $request->withAttribute('userData', $user);
                $request = $request->withAttribute('userSession', $session);
            }
        }
        return $handler->handle($request);
    }
}
