<?php


namespace app\doctrineModels;



/**
 * @Entity @Table(name="todo_element")
 */
class Todo extends BaseDoctrineModel
{
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
     * @JoinColumn(name="user_id", referencedColumnName="user_id", nullable=false)
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