<?php

namespace app\controllers;

use app\App;
use app\core\Request;
use app\DoctrineModels\User as UserModel;

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
        $user->setCreated();
        /** @var \Doctrine\ORM\EntityManager $entManager */
        $entManager = App::getDoctrineEntityManager();
        try {
            $entManager->persist($user);
            $entManager->flush();
        } catch (\Exception $dbError){
            if($dbError->getErrorCode()==1062){
                throw new \Exception('Пользователь уже существует');
            }
        }

        return ['username'=>$user->getUsername(), 'token'=>$user->getToken()];
    }

    function create(){
        $user = new UserModel();
        $user->setUsername('root');
        $user->setPassword('user');
        $user->setCreated();
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
        $user->setUpdated();
        $entManager->persist($user);
        $entManager->flush();
        $result = ['username'=>$user->getUsername(), 'token'=>$user->getToken()];
        return $result;
    }
}