<?php

namespace app\doctrineModels;

use app\App;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\PostLoad;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 * @MappedSuperclass
 * @HasLifecycleCallbacks
 */
class BaseDoctrineModel
{

    public function allowSet(){
        return [];
    }

    public function allowGet(){
        return [
            'updated',
            'created'
        ];
    }

    public function __construct(){}

    /**
     * @Column(type="datetime")
     */
    protected $created;

    /**
     * @Column(type="datetime", nullable = true)
     */
    protected $updated;

    /**
     * @PrePersist
     */
    public function beforePersist()
    {
        $this->setCreated();
        $this->setUpdated();
    }

    /**
     * @PreUpdate
     */
    public function beforeUpdate()
    {
        $this->setUpdated();
    }

    /**
     * @PostLoad
     */
    protected function PostLoad()
    {
        return $this->updated;
    }

    public function setCreated()
    {
        $this->created = new \DateTime();
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setUpdated()
    {
        $this->updated = new \DateTime();;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function setAttributes($request_attributes){
        $em_attributes = App::getDoctrineEntityManager()
            ->getClassMetadata(get_class($this))
            ->getColumnNames();

        $allow_set_attributes = $this->allowSet();
        foreach ($request_attributes as $field => $attribute){
            if(in_array($field, $em_attributes) && in_array($field, $allow_set_attributes)){
                $setter = 'set'.ucfirst($field);
                if(method_exists($this, $setter)){
                    $this->$setter($attribute);
                }
            }
        }
    }

    public function getAttributes(){
        $em_attributes = App::getDoctrineEntityManager()
            ->getClassMetadata(get_class($this))
            ->getColumnNames();
        $attributes = [];
        $allow_get_attributes = $this->allowGet();
        foreach ($em_attributes as $field => $attribute){
            if(!in_array($attribute, $allow_get_attributes)){
                continue;
            }
            $getter = 'get'.ucfirst($attribute);
            if(method_exists($this, $getter)){
                $attributes[$attribute] = $this->$getter();
            }
        }
        return $attributes;
    }

    /**
     * Поиск записи по ID
     * @param $id
     * @return null|object
     */
    public function findByPrimary($id){
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = App::getDoctrineEntityManager();
        return $em->find(get_class($this), $id);
    }

    /**
     * Удаляет по ID
     * @param $id
     * @return bool
     */
    public function deleteByPrimary($id){
        $model = $this->findByPrimary( $id );
        if(empty($model)){
            return false;
        }
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = App::getDoctrineEntityManager();
        $em->remove($model);
        $em->flush();
        return true;
    }

    /**
     * Сохраняет данные
     * @return $this
     */
    public function save(){
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = App::getDoctrineEntityManager();
        $meta = $em->getClassMetadata(get_class($this));
        $identifier = $meta->getSingleIdentifierFieldName();
        if(empty($this->{$identifier})){
            $em = App::getDoctrineEntityManager();
            $em->persist($this);
            $em->flush();
        } else {
            $em->merge($this);
            $em->flush();
        }
        return $this;
    }
}