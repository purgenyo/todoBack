<?php

namespace app\core;

/**
 * Класс осуществляет необходимую маршрутизацию
 *
 * Class RequestRouter
 * @package app\core
 */
class RequestRouter
{
    private $_default_actions = [
        'GET' =>'read',
        'POST' =>'create',
        'PUT' =>'update',
        'DELETE' =>'delete'
    ];

    private $default_status = 200;

    public $httpStatuses = [
        200, // OK
        201, // Created
        400, // Bad Request
        404, // Not Found
        405  // Method Not Allowed
    ];

    /** HTTP URL запроса */
    private $_path_info = '';

    /** HTTP Метод */
    private $_method;

    public function run(){
        $this->_setMethod();
        $this->_setPathInfo();

        try {
            $result['data'] = $this->_process();
            if(empty($result['status'])){
                $result['status'] = http_response_code();
            }
        } catch (\Exception $e){
            $result = [];
            $result['status'] = 400;
            $result['error'] = $e->getMessage();
        }

        if(!in_array($result['status'], $this->httpStatuses)){
            $result['status'] = $this->default_status;
        }

        http_response_code($result['status']);
        header('Content-Type: application/json');
        echo json_encode($result);
        die;
    }

    private function _setMethod(){
        $this->_method = $_SERVER['REQUEST_METHOD'];
    }

    private function _setPathInfo(){
        if(!empty($_SERVER['PATH_INFO'])){
            $this->_path_info = $_SERVER['PATH_INFO'];
        }
    }

    //Простой роутер
    private function _process(){
        $pattern = '/(\/)(.*)(\/|)/';
        preg_match($pattern, $this->_path_info, $result);

        if(empty($result[2])){
            throw new \Exception('Ошибка обработки запроса');
        }

        $parts = explode('/', $result[2]);
        if(empty($parts[0])){
            throw new \Exception('Ошибка обработки запроса');
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
            throw new \Exception('Ошибка запроса');
        }

        $class = new $className;
        if(!method_exists($class, $action)){
            throw new \Exception('Ошибка обработки метода запроса');
        }

        if(!$this->_isMethodAllow($class, $action)){
            http_response_code(405);
            throw new \Exception('Метод не разрешён');
        }

        return call_user_func([$class, $action]);
    }

    private function _isMethodAllow($class, $action){
        $action_method = $class->actionMap[$action];
        if($this->_method!==$action_method){
            return false;
        }

        return true;
    }

    private function _getDefaultActionByMethod(){
        if(empty($this->_default_actions[$this->_method])){
            throw new \Exception('Метод не найден');
        }
        return $this->_default_actions[$this->_method];
    }
}