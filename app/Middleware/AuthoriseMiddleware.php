<?php

namespace App\Middleware;

use App\Entity\User;
use App\Core\Request;
use App\Core\Session;
use App\Entity\UserData;
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
        private readonly ResponseFactoryInterface $responseFactory
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        if (empty($_SESSION['user'])) {
            if ($request->getMethod() === 'GET' && !$this->requestService->isXhr($request)) {
                $this->session->put('_redirect', (string) $request->getUri());
                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', '/login');
            }
        } else {
            $user = $this->entityManager->getRepository(User::class)->find($_SESSION['user']);
            if ($user) {
                $role = $user->getUsername() === 'admin' ? 'admin' : 'user';
                $request = $request->withAttribute('role', $role);
                $request = $request->withAttribute('userData', $user);
            }
        }
        return $handler->handle($request);
    }
}
