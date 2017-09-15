<?php

namespace app\controllers;

//TODO: Перенести все методы в базовый контроллер
use app\App;
use app\core\Request;
use app\doctrineModels\Todo as TodoModel;

class Todo extends BaseController
{
    public function accessActions()
    {
        return parent::accessActions();
    }

    public function read()
    {
        /** @var \Doctrine\ORM\EntityManager $entManager */
        $entManager = App::getDoctrineEntityManager();
        $todo = $entManager->getRepository('app\doctrineModels\Todo')
            ->findBy([
                'user'=>$this->getUser()
            ], ['todo_id'=>'DESC']);
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
        $todo->setUser($this->getUser());
        $todo->save();
        return $todo->getAttributes();
    }

    function update( $todo_id )
    {
        $model = new TodoModel();
        $model = $model->findByPrimary( $todo_id );
        if(empty($model)){
            return false;
        }
        $model->setAttributes(Request::getRequest());
        $model->save();
        return $model->getAttributes();
    }

    function delete( $todo_id )
    {
        $model = new TodoModel();
        return $model->deleteByPrimary($todo_id);
    }
}