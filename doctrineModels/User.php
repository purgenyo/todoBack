<?php

namespace app\doctrineModels;

/**
 * @Entity @Table(name="users")
 */
class User extends BaseDoctrineModel
{

    /** Поля доступные для записи */
    public $allow_set = [

    ];

    /** Поля доступные для чтения */
    public $allow_get = [

    ];

    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var integer
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
    
    public function __construct(){
        parent::__construct();
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
        $this->password = md5($password);
    }

    public function setToken(){
        $this->token = sha1(md5(rand(0, 50000)));
    }
    
    public function getToken(){
        return $this->token;
    }
}