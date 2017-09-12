<?php

use PHPUnit\Framework\TestCase as TestCase;

class BaseModelTest extends TestCase
{
    private $modelClass = '\app\doctrineModels\BaseDoctrineModel';

    /**
     * @dataProvider modelAttributesProvider
     * @param $attribute
     */
    public function testModelAttributes($attribute)
    {
        $this->assertClassHasAttribute($attribute, $this->modelClass);
    }

    /**
     * Дата создания
     */
    public function testCreated(){
        $baseModel = new $this->modelClass;
        $baseModel->setCreated();
        $result = $baseModel->getCreated();
        $this->assertEquals(true, $result instanceof DateTime);
    }

    /**
     * Дата обновления
     */
    public function testUpdated(){
        $baseModel = new $this->modelClass;
        $baseModel->setUpdated();
        $result = $baseModel->getUpdated();
        $this->assertEquals(true, $result instanceof DateTime);
    }

    /**
     * Действия после обновления и создания
     */
    public function testBeforeUpdateCreate(){
        $baseModel = new $this->modelClass;
        $baseModel->beforePersist();
        $created = $baseModel->getCreated();
        $updated = $baseModel->getUpdated();
        $this->assertEquals(true, $created instanceof DateTime);
        $this->assertEquals(true, $updated instanceof DateTime);
        $baseModel = new $this->modelClass;
        $baseModel->beforeUpdate();
        $updated = $baseModel->getUpdated();
        $this->assertEquals(true, $updated instanceof DateTime);
    }

    /**
     * Аттрибуты модели
     * @return array
     */
    public function modelAttributesProvider(){
        return [
            ['created'],
            ['updated'],
        ];
    }
}