<?php

namespace app\Controllers;

use app\App;
use app\DoctrineModels\User as UserModel;

class User
{
    function read(){
        //FFFFUU!
        return [
            'username'=>'fun',
            'token'=>')_(#!(UR%Jpigjd988888815'
        ];
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