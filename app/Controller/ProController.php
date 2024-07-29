<?php

namespace App\Controller;

use App\Core\View;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Core\Controller;


class ProController extends Controller
{
    public function __construct(
        private readonly View $view
    ) {
        parent::__construct($view);
    }

    public function pro(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Pro',
        ];
        return $this->render($response, 'pro/pro', $args);
    }

    public function plans(Request $request, Response $response): Response
    {
        $userData = $request->getAttribute('userData');

        $args = [
            'title' => 'Pro Plans',
            'user' => $userData
        ];

        return $this->render($response, 'pro/plans', $args);
    }
    
    public function subscribe(Request $request, Response $response): Response
    {
        $plan = $request->getAttribute('plan');
        $userData = $request->getAttribute('userData');

        $args = [
            'title' => 'Subscribe',
            'user' => $userData,
            'plan' => $plan
        ];

        return $this->render($response, 'pro/subscribe', $args);
    }
}
