<?php

use app\App;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase as TestCase;

class TodoModelTest extends TestCase
{
    private $modelClass = '\app\doctrineModels\Todo';

    private $demo_user = null;

    public function setUp()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = App::getDoctrineEntityManager();
        $schemaTool = new SchemaTool($em);
        $cmf = $em->getMetadataFactory();
        $classes = $cmf->getAllMetadata();
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);
        $this->createDemoUser();
        parent::setUp();
    }

    private function createDemoUser(){
        $user = new \app\doctrineModels\User();
        $user->setUsername('root');
        $user->setPassword('user');
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = App::getDoctrineEntityManager();
        $em->persist($user);
        $em->flush();
        $this->demo_user = $user;
    }

    private function getDemoUser(){
        return $this->demo_user;
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
     * Проверка назначения и получения свойств
     *
     * @dataProvider modelSetGetProvider
     * @param $data_set
     */
    public function testSetAndGetAttributes( $data_set ){
        $model = new $this->modelClass;
        $model->setAttributes($data_set['data']);
        $result = $model->getAttributes();
        $this->assertEquals($result, $data_set['result']);

        $model->getAttributes($data_set['data']);

    }

    /**
     * Проверка создания / чтения записи
     *
     * @dataProvider modelDataProvider
     * @param $data
     * @internal param $data_set
     * @internal param $attributes
     */
    public function testModelCreate( $data ){
        //Проверяем создание
        $data_set = $data['data_set'];
        $data_set['user'] =  $this->getDemoUser();

        //Создаем
        $create_result = $this->create($data['data_set']);
        $this->assertTrue(!empty($create_result));

        //Записываем
        $read_result = $this->readById($create_result->getTodo_id());
        $this->assertTrue(!empty($read_result));

        //Проверяем созданную запись
        $this->assertEquals($create_result, $create_result);

        //Провкра что данные именно те, которые мы отправили
        $create_success = $this->checkAttributes($this->getAttributesWithUser($create_result), $data_set);
        $read_success = $this->checkAttributes($this->getAttributesWithUser($read_result), $data_set);
        $this->assertTrue($create_success && $read_success);
    }

    /**
     * @dataProvider modelDataProvider
     * @param $data
     * @internal param $data_set
     * @internal param $attributes
     */
    public function testModelUpdate( $data ){
        //Проверяем создание
        $data_set = $data['data_set'];
        $data_set['user'] =  $this->getDemoUser();

        //Создаем
        $create_result = $this->create($data['data_set']);
        $fix_create_result = clone($create_result);
        //Обновляем
        $create_result->setAttributes($data['update_data']);
        $update_result = $create_result->save();
        $this->assertTrue(!empty($update_result));

        //записи не равны
        $this->assertNotEquals($fix_create_result, $update_result);

        //данные именно те, которые мы отправили
        $read_success = $this->checkAttributes($this->getAttributesWithUser($update_result), $data['update_data']);
        $this->assertTrue($read_success);
    }

    /**
     * @dataProvider modelDataProvider
     * @param $data
     * @internal param $data_set
     * @internal param $attributes
     */
    public function testModelDelete($data){
        $create_result = $this->create($data['data_set']);
        $model_id = $create_result->getTodo_id();
        $result = $create_result->deleteByPrimary($create_result->getTodo_id());
        $this->assertTrue($result);
        $result = $this->readById($model_id);
        $this->assertTrue(empty($result));
        $delete_model = new $this->modelClass;
        $result = $delete_model->deleteByPrimary(999999999);
        $this->assertFalse($result);
    }

    private function getAttributesWithUser( $model ){
        $attributes = $model->getAttributes();
        $attributes['user'] = $model->getUser();
        return $attributes;
    }

    private function create( $data_set ){
        $model = new $this->modelClass;
        $model->setAttributes($data_set);
        $model->setUser($this->getDemoUser());
        return $model->save();
    }

    private function readById( $id ){
        $model = new $this->modelClass;
        $data = $model->findByPrimary($id);
        if(empty($data)){
            return [];
        }
        return $model->findByPrimary($id);
    }

    private function checkAttributes( $attributes, $result_set ){
        foreach ($result_set as $field => $element){
            if($attributes[$field] !== $element){
                return false;
            }
        }
        return true;
    }

    public function testIsEditable(){
        $model = new $this->modelClass;
        $model->setIsEditable( 2 );
        $result = $model->getIsEditable();
        $this->assertTrue($result == 2);
    }

    public function modelDataProvider(){
        return [
            [
                [
                    'data_set'=>[
                        'text'=>'text',
                        'status'=>1,
                    ],
                    'update_data'=>[
                        'text'=>'text_text',
                        'status'=>2,
                    ],
                ]
            ]
        ];
    }

    public function modelAttributesProvider(){
        return [
            ['todo_id'],
            ['text'],
            ['status'],
            ['user'],
            ['updated'],
            ['created'],
            ['isEditable'],
        ];
    }

    public function modelSetGetProvider(){
        return
            [
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
                    ],
                    [
                        'data'=>[],
                        'result'=>[
                            'todo_id'=>null,
                            'text'=>null,
                            'status'=>null,
                            'updated'=>null,
                            'created'=>null
                        ]
                    ]
                ],
            ];
    }
}