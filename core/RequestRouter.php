<?php

namespace app\core;

class RequestRouter
{

    private $_default_actions = [
        'GET' =>'read',
        'POST' =>'create',
        'UPDATE' =>'update',
        'DELETE' =>'delete'
    ];

    /** HTTP URL запроса */
    private $_path_info;

    /** HTTP Метод */
    private $_method;

    public function run(){
        $this->_setMethod();
        $this->_setPathInfo();
        echo json_encode($this->_process());
        die;
    }

    private function _setMethod(){
        $this->_method = $_SERVER['REQUEST_METHOD'];
    }

    private function _setPathInfo(){
        $this->_path_info = $_SERVER['PATH_INFO'];
    }

    //Простой роутер
    private function _process(){
        $pattern = '/(\/)(.*)(\/|)/';
        preg_match($pattern, $this->_path_info, $result);
        $parts = explode('/', $result[2]);

        if(empty($parts[0])){
            throw new \Exception('Ошибка запроса');
        }

        if(empty($parts[1])){
            $parts[1] = null;
        }

        return $this->runAction($parts[0], $parts[1]);
    }

    private function runAction($controller, $action){
        if(empty($action)){
            $action = $this->_getDefaultActionByMethod();
        }

        $className = '\app\Controllers\\' . ucfirst($controller);
        if(!class_exists($className)){
            throw new \Exception('Request not found');
        }

        $class = new $className;
        if(!method_exists($class, $action)){
            return 'fail';
        }
        return call_user_func([$class, $action]);
    }

    private function _getDefaultActionByMethod(){
        if(empty($this->_default_actions[$this->_method])){
            throw new \Exception('Метод не поддерживается');
        }
        return $this->_default_actions[$this->_method];
    }
}