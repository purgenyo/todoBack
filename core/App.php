<?php

namespace app;

class App
{
    private static $_instance;
    private static $_entity_manager;
    private static $_router_config;

    public static function init(){
        self::$_instance = new App();
    }

    public static function getInstance(){
        if(is_null(self::$_instance)){
            self::init();
        }
        return self::$_instance;
    }

    public static function __callStatic( $name, $arguments )
    {
        return call_user_func_array( array(self::getInstance(), $name), $arguments);
    }

    private function setDoctrineEntityManager( $entity_manager ){
        self::$_entity_manager = $entity_manager;
    }

    private function getDoctrineEntityManager(){
        if(empty(self::$_entity_manager)){
            throw new \Exception('Ошибка');
        }
        return self::$_entity_manager;
    }

    private function getRouterConfig(){
        if(empty(self::$_router_config)){
            throw new \Exception('Ошибка. Необходимо настроить роутер');
        }
        return self::$_router_config;
    }

    private function setRouterConfig( $router_config ){
        self::$_router_config = $router_config;
    }

}