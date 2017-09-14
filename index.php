<?php
require_once "bootstrap.php";
(new \app\core\RequestRouter( \app\App::getRouterConfig() ))->run();
