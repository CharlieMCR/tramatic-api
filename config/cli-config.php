<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require __DIR__ . '/../src/bootstrap.php';

$em = $app->getContainer()->get('em');

return ConsoleRunner::createHelperSet($em);
