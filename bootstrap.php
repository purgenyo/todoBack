<?php

use app\App;
use Doctrine\ORM\Tools\Setup;

/** Загрузка классов */
require_once "vendor/autoload.php";

/** Конфигурируем doctrine */
$isDevMode = true;
$doctrineModelDirs = [__DIR__ . "/doctrineModels"];
$config = Setup::createAnnotationMetadataConfiguration($doctrineModelDirs, $isDevMode);

$dbParams = require (__DIR__ . "/config/db_config.php");
$router_config = require (__DIR__ . "/config/route_config.php");

$entityManager = \Doctrine\ORM\EntityManager::create($dbParams, $config);

App::setDoctrineEntityManager($entityManager);
App::setRouterConfig($router_config);
