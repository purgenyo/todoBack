<?php

class RequestRouterTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateCollections(){


        $_SERVER['PATH_INFO'] = '/';
        $_SERVER['REQUEST_METHOD'] = '/';

        $router = new \app\core\RequestRouter($this->getRouter());
        $collections = $router->createRouteCollections();
        $collection = $collections['user'];
        $this->assertTrue(true);
    }


    public function getRouter(){
        return [
            'user'=>[
                'POST'=>[
                    'registration'=>'registration',
                    'registration/id:[0-9]+'=>'registration',
                    'todo_id:\d+'=>'delete',
                    'test\test2\test3\test4'=>'delete',
                ],
                'GET'=>[
                    'test'=>'testGet',
                ],
                'PUT'=>[
                    'test'=>'testPut',
                ],
                'DELETE'=>[
                    'test'=>'testDelete',
                ],
            ]
        ];
    }
}