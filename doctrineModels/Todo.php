<?php


namespace app\doctrineModels;
use app\App;


/**
 * @Entity @Table(name="todo_element")
 */
class Todo extends BaseDoctrineModel
{

    /** Поля доступные для записи */
    public function allowSet(){
        $allow_set = parent::allowSet();
        $allow_set[] = 'text';
        $allow_set[] = 'user';
        $allow_set[] = 'status';
        return $allow_set;
    }

    /** Поля доступные для чтения */
    public function allowGet(){
        $allow_get = parent::allowGet();
        $allow_get[] = 'todo_id';
        $allow_get[] = 'text';
        $allow_get[] = 'status';
        return $allow_get;
    }


    /**
     * @var int
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    protected $todo_id;

    /**
     * @Column(type="string", length=1024, unique=false, nullable=false)
     */
    protected $text;

    /**
     * @var int
     * @Column(type="integer", nullable=false, options={"default": 1})
     */
    protected $status = 1;

    /**
     * @OneToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="user_id", nullable=true)
     */
    protected $user;

    public function getTodo_id()
    {
        return $this->todo_id;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus(){
        return $this->status;
    }


}