<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new \Dotenv\Dotenv(__DIR__ . '/..');
$dotenv->load();

$settings = require __DIR__ . '/settings.php';
$app      = new \Slim\App($settings);

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

require __DIR__ . '/dependencies.php';