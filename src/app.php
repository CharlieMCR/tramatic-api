<?php

require __DIR__ . '/../vendor/autoload.php';

$config = [];
$app = new \Slim\App($config);

require __DIR__ . '/../src/routes.php';

$app->run();