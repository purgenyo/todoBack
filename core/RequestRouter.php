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

    }

    /** HTTP URL запроса */
    private $_path_info = '';

    /** HTTP Метод */
    private $_method;

    private function _getRoute(){
        $collections = $this->createRouteCollections();

        $controller = '';
        $url_api_parts = explode('/', $this->_path_info);

        if(isset($url_api_parts[0])){
            $controller = $url_api_parts[0];
        }

        if(empty($collections[$controller])){
            throw new \Exception('Невозможно обработать запрос');
        }

        $controller_collection = $collections[$controller];
        $context = $this->getContext();
        $matcher = new UrlMatcher($controller_collection, $context);
        $result_request = '/' . $this->_path_info;
        return $matcher->match( $result_request );
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
                    $result_url = $controller_id;
                    if(!empty($result['parts'])){
                        $result_url .= '/' . $result['parts'];
                    }
                    $route = new Route(
                        $result_url,
                        ['_controller' => ucfirst($controller_id)],
                        $result['requirements'],
                        [],
                        '',
                        [],
                        [$method, 'OPTIONS']
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
            $regexp_params = explode(':', $part);
            if(count($regexp_params)==2){
                $requirements[$regexp_params[0]] = $regexp_params[1];
                $result_params_parts[] =  '{' . $regexp_params[0] . '}';
            } elseif(count($regexp_params)==1) {
                $result_params_parts[] = $regexp_params[0];
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

    private function _process(){
        $route = $this->_getRoute();
        $params = $this->_getParamsFromRouter($route);
        return $this->runAction($route['_controller'], $route['_route'], $params);
    }

    private function _getParamsFromRouter($params){
        unset($params['_controller']);
        unset($params['_route']);
        return $params;
    }

    private function runAction($controller, $action, $params = []){

        $className = '\app\Controllers\\' . $controller;
        if(!class_exists($className)){
            throw new \Exception('Контроллер не найден');
        }

        $class = new $className;
        if(!method_exists($class, $action)){
            throw new \Exception('Метод не существует');
        }

        return call_user_func_array([$class, $action], $params);
    }
}