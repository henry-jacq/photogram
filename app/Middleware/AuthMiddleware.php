<?php

namespace App\Middleware;

use App\Core\Config;
use App\Core\Session;
use App\Entity\UserSession;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly Session $session,
        private readonly EntityManager $em,
        private readonly ResponseFactoryInterface $responseFactory,
    )
    {
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Protection against session fixation attacks
        $name = $this->config->get('session.name');
        $sessionId = $request->getCookieParams()[$name] ?? null;
        
        if ($sessionId !== null) {
            $session = $this->em->getRepository(UserSession::class)
            ->findOneBy(['sessionId' => $sessionId]);
            
            if ($session !== null) {
                $this->session->regenerate();
                $this->session->forget('user');
                $this->session->forget('userSession');
                return $handler->handle($request);
            }
        }
        
        if ($this->session->get('user') !== null) {
            return $this->responseFactory
            ->createResponse(302)
            ->withHeader('Location', '/home');
        }
        
        return $handler->handle($request);
    }
}