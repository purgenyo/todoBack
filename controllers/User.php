<?php

namespace app\controllers;

use app\App;
use app\core\Request;
use app\doctrineModels\User as UserModel;

/**
 * Class User
 * @package app\controllers
 */
class User extends BaseController
{
    public function accessActions()
    {
        $actions = parent::accessActions();
        $actions[] = 'registration';
        $actions[] = 'login';
        return $actions;
    }

    public $actionMap = [
        'registration'=>'POST',
        'login'=>'POST',
        'create'=>'POST'
    ];

    function registration(){
        $user = new UserModel();
        $user->setAttributes(Request::getRequest());
        return $user->save()->getAttributes();
    }


    function login(){
        $user = new UserModel();
        $user->setAttributes(Request::getRequest());
        return $user->login()->getAttributes();
    }
}