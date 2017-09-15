<?php

namespace app\controllers;

use app\core\Request;
use app\doctrineModels\User;

class BaseController
{
    private $_user = null;
    private $_action = null;

    /**
     * Методы доступные без авторизации
     *
     * @return array
     */
    public function accessActions(){
        return [];
    }

    function __construct()
    {

    }

    /**
     * @throws \Exception
     */
    public function beforeRun(){
        if(!($this->processUserAccess())){
            http_response_code(401);
            throw new \Exception('Доступ закрыт');
        }
    }

    /**
     * Проверка авторизации пользователя
     * @return bool
     * @throws \Exception
     */
    public function processUserAccess(){

        if(in_array($this->getAction(), $this->accessActions())){
            return true;
        }

        $token = Request::getToken();
        if($token===false){
            http_response_code(401);
            throw new \Exception('Доступ закрыт');
        }
        $user = (new User)->getUserByToken($token);
        if(empty($user)){
            http_response_code(401);
            throw new \Exception('Ошибка авторизации');
        }
        $this->setUser($user);
        return true;
    }

    public function setAction($action){
        $this->_action = $action;
    }

    public function getAction(){
        return $this->_action;
    }

    public function setUser( $user ){
        $this->_user = $user;
    }

    public function getUser(){
        return $this->_user;
    }
}