<?php

$files = [
    'app' => 'app.php',
];

$container->set('config', function () use ($files) {
    return new \Lib\Settings\Settings($files);
});


# end of file