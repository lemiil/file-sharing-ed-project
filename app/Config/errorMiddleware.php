<?php

use Psr\Http\Message\ServerRequestInterface;

$app->addRoutingMiddleware();

$confApp = $container->get('config')->getAlias('app');

$customErrorHandler = $confApp['ErrorMiddleware']['customErrorHandler'] ?? true;
$displayErrorDetails = $confApp['ErrorMiddleware']['displayErrorDetails'] ?? false;
$logErrors = $confApp['ErrorMiddleware']['logErrors'] ?? false;
$logErrorDetails = $confApp['ErrorMiddleware']['logErrorDetails'] ?? false;

if ($customErrorHandler) {
    $customErrorHandler = function (
        ServerRequestInterface $request,
        Throwable $exception
    ) use ($app) {
        return \App\Controllers\Page404\Page404::response($app, $exception, $request);
    };

    if ($container->has('logger'))
        $errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logErrors, $logErrorDetails, $container->get('logger'));
    else
        $errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logErrors, $logErrorDetails);

    $errorMiddleware->setDefaultErrorHandler($customErrorHandler);
} else {
    if ($container->has('logger'))
        $app->addErrorMiddleware($displayErrorDetails, $logErrors, $logErrorDetails, $container->get('logger'));
    else
        $app->addErrorMiddleware($displayErrorDetails, $logErrors, $logErrorDetails);
}

# end of file
