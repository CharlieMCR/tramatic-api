<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new \Dotenv\Dotenv(__DIR__ . '/..');
$dotenv->load();

$settings = require __DIR__ . '/../src/settings.php';
$app      = new \Slim\App($settings);

require __DIR__ . '/../src/dependencies.php';