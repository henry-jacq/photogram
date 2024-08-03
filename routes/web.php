<?php

use Slim\App;
use App\Controller\ApiController;
use App\Controller\AuthController;
use App\Controller\ProController;
use App\Controller\UserController;
use App\Middleware\AuthMiddleware;
use App\Controller\SupportController;
use Slim\Routing\RouteCollectorProxy;
use App\Middleware\AuthoriseMiddleware;

return function (App $app) {

    // User Routes
    // Prevent user routes access for not logged in users
    $app->group('/', function (RouteCollectorProxy $group) {
        $group->get('home', [UserController::class, 'home']);
        $group->get('discover', [UserController::class, 'discover']);
        $group->get('settings/{tab}', [UserController::class, 'settings']);
        $group->get('profile/edit', [UserController::class, 'edit']);
        $group->get('profile/{name}', [UserController::class, 'profile']);
        $group->get('files/{category}/{image}', [UserController::class, 'files']);
        $group->get('logout', [UserController::class, 'logout']);
        $group->get('pro/plans', [ProController::class, 'plans']);
        $group->get('pro/subscribe/{plan}', [ProController::class, 'subscribe']);
    })->add(AuthoriseMiddleware::class);

    // Auth Routes
    // Prevent auth routes access for logged in users
    $app->group('/', function (RouteCollectorProxy $group) {
        $group->get('', [AuthController::class, 'landingView']);
        $group->get('onboarding', [AuthController::class, 'onboardView']);
        $group->get('login', [AuthController::class, 'loginView']);
        $group->get('register', [AuthController::class, 'registerView']);
        $group->get('forgot-password', [AuthController::class, 'forgotPassword']);
    })->add(AuthMiddleware::class);

    // Help and support routes
    $app->group('/', function (RouteCollectorProxy $group) {
        $group->get('privacy-policy', [SupportController::class, 'privacyPolicy']);
        $group->get('terms-and-conditions', [SupportController::class, 'tac']);
        $group->get('contact-us', [SupportController::class, 'contactUs']);
        $group->get('about-us', [SupportController::class, 'aboutUs']);
    })->add(AuthoriseMiddleware::class);

    // API Routes
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->any('/{namespace}/{resource}[/{params:.*}]', [ApiController::class, 'process']);
    })->add(AuthoriseMiddleware::class);
};
