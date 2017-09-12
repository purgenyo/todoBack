<?php

namespace app\controllers;

//TODO: Перенести все методы в базовый контроллер
use app\App;
use app\core\Request;
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
        $model = new TodoModel();
        $model = $model->findByPrimary( $todo_id );
        if(empty($model)){
            return [];
        } else {
            return $model->getAttributes();
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
        $model = new TodoModel();
        $model = $model->findByPrimary( $todo_id );
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = App::getDoctrineEntityManager();
        $em->remove($model);
        $em->flush();
        return true;
    }


}