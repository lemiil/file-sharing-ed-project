<?php


namespace App\Controllers\Page404;

use Psr\Http\Message\ServerRequestInterface;
use Fig\Http\Message\StatusCodeInterface;

class Page404
{
    public static function response($app, \Throwable $exception, ServerRequestInterface $request)
    {
        $container = $app->getContainer();
        if ($container->has('logger'))
            $container->get('logger')->error('404', [$exception->getMessage()]);
        $body = file_get_contents(__DIR__ . '/404.template.php');
        $response = $app->getResponseFactory()->createResponse(StatusCodeInterface::STATUS_NOT_FOUND);
        $response->getBody()->write($body);

        return $response;
    }
}

# end of file
