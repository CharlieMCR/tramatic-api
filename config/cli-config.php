<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require __DIR__ . '/../vendor/autoload.php';

$settings = include __DIR__ . '/../src/settings.php';
$app      = new \Slim\App($settings);

require __DIR__ . '/../src/dependencies.php';

$em = $app->getContainer()->get('em');

return ConsoleRunner::createHelperSet($em);
