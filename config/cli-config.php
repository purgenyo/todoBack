<?php
use app\App;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once  __DIR__ . '/../bootstrap.php';
$entityManager = App::getDoctrineEntityManager();
return ConsoleRunner::createHelperSet($entityManager);