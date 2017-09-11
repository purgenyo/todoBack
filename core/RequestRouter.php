<?php

namespace app\core;

use app\App;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

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

    function __construct()
    {
        $this->_setMethod();
        $this->_setPathInfo();
        $this->_configureRoute();
    }

    private $_controller = '';
    private $_action = '';

    /** HTTP URL запроса */
    private $_path_info = '';

    /** HTTP Метод */
    private $_method;

    public function _configureRoute(){
        $collections = $this->createRouteCollections();

        $controller = '';
        $url_api_parts = explode('/', $this->_path_info);

        if(isset($url_api_parts[0])){
            $controller = $url_api_parts[0];
        }

        if(!empty($collections[$controller])){
            $controller_collection = $collections[$controller];
        }
        $context = $this->getContext();

        $matcher = new UrlMatcher($collections, $context);

        $parameters_1 = $matcher->match('/todo/4/');
        $parameters_2 = $matcher->match('/todo/');

        $controller = '';
        $router_map = App::getRouterConfig();
        if(!empty($router_map[$controller][$this->_method])){
            $controller_config = $router_map[$controller][$this->_method];
            $a = 1;
        }
    }

    public function getContext(){
        return  new RequestContext('/', $this->_method);
    }

    public function createRouteCollections(){
        $router_map = App::getRouterConfig();
        $routers_collections = [];
        foreach ($router_map as $controller_id => $controller_methods){
            $routes = new RouteCollection();
            foreach ($controller_methods as $method => $actions){
                foreach ($actions as $url => $action){
                    $result = $this->getParams($url);
                    $result_url = $controller_id . '/' . $result['parts'];
                    $route = new Route(
                        $result_url,
                        ['_controller' => ucfirst($controller_id)],
                        $result['requirements'],
                        [],
                        '',
                        [],
                        [$method]
                    );
                    $routes->add($action, $route);
                }
            }
            $routers_collections[$controller_id] = $routes;
        }
        return $routers_collections;
    }

    private function getParams( $match_str ){
        $url_parts = explode('/', $match_str);

        $result_params_parts = [];
        $requirements = [];

        foreach ($url_parts as $part){
            $regexp_paramats = explode(':', $part);
            if(count($regexp_paramats)==2){
                $requirements[$regexp_paramats[0]] = $regexp_paramats[1];
                $result_params_parts[] =  '{' . $regexp_paramats[0] . '}';
            } elseif(count($regexp_paramats)==1) {
                $result_params_parts[] = $regexp_paramats[0];
            }
        }

        $result['requirements'] = $requirements;
        $result['parts'] = implode('/', $result_params_parts);
        return $result;
    }

    public function run(){

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
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        echo json_encode($result);
        die;
    }

    private function _setMethod(){
        $this->_method = $_SERVER['REQUEST_METHOD'];
    }

    private function _setPathInfo(){
        if(!empty($_SERVER['PATH_INFO'])){
            $this->_path_info = trim($_SERVER['PATH_INFO'], '/');
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
        if(empty($class->actionMap[$action])){
            return false;
        }

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