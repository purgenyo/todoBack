<?php

namespace app\controllers;

use app\core\Request;
use app\doctrineModels\User;

class BaseController
{
    private $_user = null;
    private $_action = null;

    public function accessActions(){
        return [];
    }

    function __construct()
    {

    }

    public function beforeRun(){
        if(!($this->processUserAccess())){
            http_response_code(403);
            throw new \Exception('Доступ закрыт');
        }
    }

    public function processUserAccess(){

        if(in_array($this->getAction(), $this->accessActions())){
            return true;
        }

        $token = Request::getToken();
        if($token===false){
            http_response_code(403);
            throw new \Exception('Доступ закрыт');
        }
        $user = (new User)->getUserByToken($token);
        if(empty($user)){
            throw new \Exception('Ошибка авторизации');
        }
        $this->setUser((new User)->getUserByToken($token));
        return true;
    }

    public $entityModel = '';

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