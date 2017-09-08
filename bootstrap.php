<?php

use app\App;
use Doctrine\ORM\Tools\Setup;

/** Загрузка классов */
require_once "vendor/autoload.php";
require_once __DIR__ . "/config/App.php";
require_once __DIR__ . "/Controllers/User.php";
require_once __DIR__ . "/DoctrineModels/User.php";

require_once __DIR__ . "/core/RequestRouter.php";

/** Конфигурируем doctrine */
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/DoctrineModels"), $isDevMode);
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => '',
    'dbname'   => 'todo',
);
$entityManager = \Doctrine\ORM\EntityManager::create($dbParams, $config);
App::setDoctrineEntityManager($entityManager);