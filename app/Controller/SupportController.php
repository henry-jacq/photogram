<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class SupportController extends Controller
{
    public function __construct(
        private readonly View $view
    ) {
        parent::__construct($view);
    }
    
    public function privacyPolicy(Request $request, Response $response): Response
    {
        $userData = $request->getAttribute('userData');

        $args = [
            'title' => 'Privacy Policy',
            'user' => $userData,
        ];
        return $this->render($response, 'support/privacy', $args);
    }

    public function tac(Request $request, Response $response): Response
    {
        $userData = $request->getAttribute('userData');

        $args = [
            'title' => 'Terms and Conditions',
            'user' => $userData,
        ];
        return $this->render($response, 'support/tac', $args);
    }

    public function contactUs(Request $request, Response $response): Response
    {
        $userData = $request->getAttribute('userData');
        $args = [
            'title' => 'Contact Us',
            'user' => $userData,
        ];
        return $this->render($response, 'support/contact', $args);
    }

    public function aboutUs(Request $request, Response $response): Response
    {
        $userData = $request->getAttribute('userData');

        $args = [
            'title' => 'About Us',
            'user' => $userData,
        ];
        return $this->render($response, 'support/about', $args);
    }
}