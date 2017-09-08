<?php

namespace app\DoctrineModels;

/**
 * @Entity @Table(name="users")
 */
class User
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var string
     */
    protected $user_id;

    /**
     * @Column(type="string", unique=true)
     * @var string
     */
    protected $username;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $password;

    /**
     * @Column(type="string", nullable = true)
     * @var string
     */
    protected $token;

    /**
     * @Column(type="datetime")
     */
    protected $created;

    /**
     * @Column(type="datetime", nullable = true)
     */
    protected $updated;

    public function __construct(){

    }

    public function getId()
    {
        return $this->user_id;
    }

    public function getUsername()
    {
        return $this->username;
    }
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setCreated(){
        $this->created = new \DateTime();
    }
    public function getCreated(){
        return $this->created;
    }

    public function setUpdated(){
        $this->updated = new \DateTime();;
    }
    public function getUpdated(){
        return $this->updated;
    }

    public function setToken(){
        //Простая генерация токена
        $this->token = sha1(md5(rand(0, 50000)));
    }
    public function getToken(){
        return $this->token;
    }

    /** @PreFlush */
    public function onPrePersist()
    {
        //TODO: Починить
        $this->created = new \DateTime("now");
    }

    /** @PreUpdate */
    public function onPreUpdate()
    {
        //TODO: Починить
        $this->updated = new \DateTime("now");
    }
}