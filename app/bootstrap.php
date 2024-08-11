<?php

use Slim\Factory\AppFactory;
use DI\Container;

require BASE_DIR . 'vendor/autoload.php';
require BASE_DIR . 'src/Core/functions.php';
$getID3 = new getID3;
$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();



require BASE_DIR . 'app/Config/container.php';
require BASE_DIR . 'app/Config/settings.php';
require BASE_DIR . 'app/Config/middleware.php';
require BASE_DIR . 'app/Config/errorMiddleware.php';
require BASE_DIR . 'app/Config/routes.php';


$app->run();

# end of file
