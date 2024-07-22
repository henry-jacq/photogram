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
            $user = $this->entityManager->getRepository(User::class)->find($_SESSION['user']);
            $key = $this->session->get('sessionKey');
            if ($user && $key) {
                // TODO: Validate user session key
                $userSession = $this->authService->getUserSession($key);
                $role = $user->getUsername() === 'admin' ? 'admin' : 'user';
                $request = $request->withAttribute('role', $role);
                $request = $request->withAttribute('userData', $user);
                $request = $request->withAttribute('userSession', $userSession);
            }
        }
        return $handler->handle($request);
    }
}
