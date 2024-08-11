<?php

use Slim\Views\Twig;
use Slim\Routing\RouteCollectorProxy;


$app->get('/', function ($request, $response, $args) {
    $twig = $this->get(Twig::class);
    return $twig->render($response, 'main.template.html');
});

$app->get('/upload', \App\Controllers\Files\FileUpload::class)->setName('upload');
$app->post('/upload', \App\Controllers\Files\FileUpload::class)->setName('upload');
$app->get('/list', \App\Controllers\Files\FilesList::class)->setName('list');
$app->get('/file/{id}', \App\Controllers\Files\FilePage::class)->setName('filepage');
$app->get('/download/{id}', \App\Controllers\Files\FileDownload::class)->setName('filedownload');
$app->get('/stats', \App\Controllers\Stats\StatsPage::class)->setName('stats');


# end of file
