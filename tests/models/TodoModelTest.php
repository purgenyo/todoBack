<?php

use app\App;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase as TestCase;

class TodoModelTest extends TestCase
{
    private $modelClass = '\app\doctrineModels\Todo';

    public function setUp()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = App::getDoctrineEntityManager();
        $schemaTool = new SchemaTool($em);
        $cmf = $em->getMetadataFactory();
        $classes = $cmf->getAllMetadata();
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);
        parent::setUp();
    }

    /**
     * @dataProvider modelAttributesProvider
     * @param $attribute
     */
    public function testModelAttributes($attribute)
    {
        $this->assertClassHasAttribute($attribute, $this->modelClass);
    }

    /**
     * Проверка назначения свойств
     *
     * @dataProvider modelDataProvider
     * @param $attributes
     */
    public function testSetAndGetAttributes( $attributes ){
        $todo = new $this->modelClass;
        $todo->setAttributes($attributes['data']);
        $result = $todo->getAttributes();
        $this->assertEquals($result, $attributes['result']);
    }

    public function modelAttributesProvider(){
        return [
            ['todo_id'],
            ['text'],
            ['status'],
            ['user'],
            ['updated'],
            ['created'],
        ];
    }

    public function modelDataProvider(){
        return[
            [
                [
                    'data'=>[
                        'todo_id'=>'test',
                        'user'=>666,
                        'text'=>'text',
                        'status'=>555,
                        'updated'=>555,
                        'created'=>555
                    ],
                    'result'=>[
                        'todo_id'=>null,
                        'text'=>'text',
                        'status'=>555,
                        'updated'=>null,
                        'created'=>null
                    ]
                ]
            ],
        ];
    }
}