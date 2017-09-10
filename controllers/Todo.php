<?php

namespace app\controllers;


use app\App;
use app\core\Request;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use app\doctrineModels\Todo as TodoModel;
class Todo
{
    public $actionMap = [
        'read'=>'GET',
        'create'=>'POST',
        'update'=>'PUT'
    ];

    public function read(){
        /** @var QueryBuilder $builder */
        $builder = App::getDoctrineEntityManager()->createQueryBuilder();
        $result = $builder->select('p')
            ->from('app\doctrineModels\Todo', 'p')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        return $result;
    }

    function create(){
        $todo = new TodoModel();
        $todo->setAttributes(Request::getRequest());
        /** @var \Doctrine\ORM\EntityManager $entManager */
        $entManager = App::getDoctrineEntityManager();
        $entManager->persist($todo);
        $entManager->flush();
        return $todo;
    }

    function update(){
        $todo = new TodoModel();
        $todo->setAttributes(Request::getRequest());
        /** @var \Doctrine\ORM\EntityManager $entManager */
        $entManager = App::getDoctrineEntityManager();
        $entManager->merge($todo);
        $entManager->flush();
        return (array)$todo;
    }
}