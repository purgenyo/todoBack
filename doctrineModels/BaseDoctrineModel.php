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

    private function setCreated()
    {
        $this->created = new \DateTime();
    }

    protected function getCreated()
    {
        return $this->created;
    }

    private function setUpdated()
    {
        $this->updated = new \DateTime();;
    }

    protected function getUpdated()
    {
        return $this->updated;
    }

    function setAttributes($request_attributes){
        $em_attributes = App::getDoctrineEntityManager()
            ->getClassMetadata(get_class($this))
            ->getColumnNames();

        foreach ($request_attributes as $field => $attribute){
            if(in_array($field, $em_attributes)){
                $setter = 'set'.ucfirst($field);
                if(method_exists($this, $setter)){
                    $this->$setter($attribute);
                }
            }
        }

    }

}