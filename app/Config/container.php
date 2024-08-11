<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Slim\Views\Twig;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Container\ContainerInterface;
use App\Controllers\FileControllers\FileUpload;
use App\Modules\FilesTable;
// Логи
$container->set('logger', function () {
    $log = new Logger('general');
    $log->pushHandler(new StreamHandler(BASE_DIR . 'var/log/app.log', Logger::INFO));

    return $log;
});
// Шаблонизатор
$container->set(Twig::class, function () {
    return Twig::create(
        BASE_DIR . 'resources/views', //['cache' => 'var/cache']
    );
});
// Директория загруженных файлов
$container->set('upload_directory', BASE_DIR . '/var/uploadedFiles');


# end of file
