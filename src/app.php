<?php

require __DIR__ . '/../vendor/autoload.php';

$settings = require __DIR__ . '/settings.php';
$app = new \Slim\App($settings);

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

$dotenv = new \Dotenv\Dotenv(__DIR__ . '/..');
$dotenv->load();

require __DIR__ . '/dependencies.php';

require __DIR__ . '/routes.php';

$app->run();