<?php

namespace app\controllers;

//TODO: Перенести все методы в базовый контроллер
use app\App;
use app\core\Request;
use Doctrine\ORM\Query;
use app\doctrineModels\Todo as TodoModel;
class Todo
{

    public function read()
    {
        /** @var \Doctrine\ORM\EntityManager $entManager */
        $entManager = App::getDoctrineEntityManager();
        $todo = $entManager->getRepository('app\doctrineModels\Todo')
            ->findBy(['status'=>1], ['todo_id'=>'DESC']);
        if(empty($todo)){
            return [];
        } else {
            $result = [];
            foreach ($todo as $t){
                $result[] = $t->getAttributes();
            }
            return $result;
        }
    }

    public function readOne( $todo_id )
    {
        /** @var \Doctrine\ORM\EntityManager $entManager */
        $entManager = App::getDoctrineEntityManager();
        $todo = $entManager->find('app\doctrineModels\Todo', $todo_id);
        if(empty($todo)){
            return [];
        } else {
            return $todo->getAttributes();
        }
    }

    function create()
    {
        $todo = new TodoModel();
        $todo->setAttributes(Request::getRequest());
        /** @var \Doctrine\ORM\EntityManager $entManager */
        $entManager = App::getDoctrineEntityManager();
        $entManager->persist($todo);
        $entManager->flush();
        return $todo->getAttributes();
    }

    function update( $todo_id )
    {
        return $todo_id;
    }

    function delete( $todo_id )
    {
        return $todo_id;
    }


}