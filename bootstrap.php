<?php

use app\App;
use Doctrine\ORM\Tools\Setup;

/** Загрузка классов */
require_once "vendor/autoload.php";
require_once __DIR__ . "/config/App.php";

require_once __DIR__ . "/controllers/User.php";

require_once __DIR__ . "/doctrineModels/User.php";

require_once __DIR__ . "/core/RequestRouter.php";
require_once __DIR__ . "/core/Request.php";

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