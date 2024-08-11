<?php

use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
// для включения нужно убрать комментарий
// $app->add(\App\Middleware\BeforeMiddleware::class);
// $app->add(\App\Middleware\AfterMiddleware::class);
$app->add(TwigMiddleware::create($app, $container->get(Twig::class)));
$app->addRoutingMiddleware();
