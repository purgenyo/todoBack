<?php

use app\App;
use Doctrine\ORM\Tools\Setup;

/** Загрузка классов */
require_once "vendor/autoload.php";
require_once __DIR__ . "/doctrineModels/BaseDoctrineModel.php";

require_once __DIR__ . "/config/App.php";
require_once __DIR__ . "/controllers/User.php";
require_once __DIR__ . "/controllers/Todo.php";

require_once __DIR__ . "/doctrineModels/User.php";
require_once __DIR__ . "/doctrineModels/Todo.php";


require_once __DIR__ . "/core/RequestRouter.php";
require_once __DIR__ . "/core/Request.php";

/** Конфигурируем doctrine */
$isDevMode = true;
$doctrineModelDirs = [__DIR__ . "/doctrineModels"];
$config = Setup::createAnnotationMetadataConfiguration($doctrineModelDirs, $isDevMode);

//TODO: в отдельный файл
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => 'root',
    'dbname'   => 'todo',
);
$entityManager = \Doctrine\ORM\EntityManager::create($dbParams, $config);
App::setDoctrineEntityManager($entityManager);