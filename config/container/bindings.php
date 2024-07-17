<?php

use Slim\App;
use App\Core\Auth;
use App\Core\View;
use App\Core\Config;
use App\Core\Request;
use App\Core\Session;
use function DI\create;
use Doctrine\ORM\ORMSetup;
use Slim\Factory\AppFactory;
use Doctrine\ORM\EntityManager;
use App\Interfaces\SessionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

return [
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        $middleware = require CONFIG_PATH . DIRECTORY_SEPARATOR . '/middleware.php';
        $router     = require ROUTES_PATH . DIRECTORY_SEPARATOR . 'web.php';

        $app = AppFactory::create();

        $router($app);

        $middleware($app);

        return $app;
    },
    Config::class => create(Config::class)->constructor(
        require CONFIG_PATH . '/app.php'
    ),
    EntityManager::class => fn (Config $config) => EntityManager::create(
        $config->get('doctrine.connection'),
        ORMSetup::createAttributeMetadataConfiguration(
            $config->get('doctrine.entity_dir'),
            $config->get('doctrine.dev_mode')
        )
    ),
    ResponseFactoryInterface::class => fn(App $app) => $app->getResponseFactory(),
    Request::class => function(ContainerInterface $container) {
        return new Request($container->get(SessionInterface::class));
    },
    Auth::class => function(ContainerInterface $container){
        return new Auth($container->get(EntityManager::class), $container->get(SessionInterface::class));
    },
    View::class => function (ContainerInterface $container) {
        return new View($container->get(Config::class));
    },
    SessionInterface::class => function (ContainerInterface $container) {
        return new Session($container->get(Config::class));
    },
    ZipArchive::class => function () {
        return new ZipArchive();
    }
];
