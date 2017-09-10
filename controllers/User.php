<?php

namespace app\controllers;

use app\App;
use app\core\Request;
use app\doctrineModels\User as UserModel;

/**
 * Class User
 * @package app\controllers
 */
class User
{
    public $actionMap = [
        'registration'=>'POST',
        'login'=>'POST',
        'create'=>'POST'
    ];

    function registration(){
        $request_data = Request::getRequest();
        $username = $request_data['username'];
        $password = $request_data['password'];

        $user = new UserModel();
        $user->setUsername($username);
        $user->setPassword($password);
        /** @var \Doctrine\ORM\EntityManager $entManager */
        $entManager = App::getDoctrineEntityManager();
        try {
            $entManager->persist($user);
            $entManager->flush();
        } catch (\Exception $dbError){
            throw new \Exception($dbError->getMessage());
        }

        return ['username'=>$user->getUsername(), 'token'=>$user->getToken()];
    }

    function create(){
        $user = new UserModel();
        $user->setUsername('root');
        $user->setPassword('user');
        /** @var \Doctrine\ORM\EntityManager $entManager */
        $entManager = App::getDoctrineEntityManager();
        $entManager->persist($user);
        $entManager->flush();
        $result = ['username'=>$user->getUsername(), 'token'=>$user->getToken()];
        return $result;
    }

    function login(){
        /** @var \Doctrine\ORM\EntityManager $entManager */
        $entManager = App::getDoctrineEntityManager();
        $user = $entManager->find("app\\DoctrineModels\\User", (int)8);
        $user->setToken();
        $entManager->persist($user);
        $entManager->flush();
        $result = ['username'=>$user->getUsername(), 'token'=>$user->getToken()];
        return $result;
    }
}