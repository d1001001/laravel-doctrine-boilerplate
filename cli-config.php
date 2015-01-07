<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/start.php';
$em = $app->make('Doctrine\ORM\EntityManager');
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($em);
