<?php

namespace app\doctrineModels;
use app\App;
use Doctrine\ORM\QueryBuilder;

/**
 * @Entity @Table(name="users")
 */
class User extends BaseDoctrineModel
{
    /** Поля доступные для записи */
    public function allowSet(){
        $allow_set = parent::allowSet();
        $allow_set[] = 'user_id';
        $allow_set[] = 'username';
        $allow_set[] = 'password';
        return $allow_set;
    }

    /** Поля доступные для чтения */
    public function allowGet(){
        $allow_get = parent::allowGet();
        $allow_get[] = 'user_id';
        $allow_get[] = 'username';
        $allow_get[] = 'token';
        return $allow_get;
    }

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

    /**
     * @ManyToMany(targetEntity="TodoShare", inversedBy="todoShare", cascade={"remove", "persist"})
     * @JoinTable(name="todo_share_link",
     * joinColumns={
     *     @JoinColumn(name="user_id", referencedColumnName="user_id", onDelete="CASCADE"),
     * },
     * inverseJoinColumns={
     *     @JoinColumn(name="todo_share_id", referencedColumnName="todo_share_id", onDelete="CASCADE")}
     * )
     */
    protected $todoShare;
    
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

    public function getPasswordHash( $password ){
        return md5($password);
    }

    public function getPassword()
    {
        return $this->password;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setToken(){
        $this->token = sha1(md5(rand(0, 50000)));
    }
    
    public function getToken(){
        return $this->token;
    }

    public function login(){
        /** @var \Doctrine\ORM\EntityManager $entManager */
        $em = App::getDoctrineEntityManager();
        /** @var QueryBuilder $qb */
        $qb = $em->createQueryBuilder();
        $result = $qb->select(['u'])
            ->from(get_class($this), 'u')
            ->where('u.username = :username and u.password = :password')
            ->setParameter('username', $this->getUsername())
            ->setParameter('password', $this->getPasswordHash($this->getPassword()))
            ->getQuery()->getResult();
        //return $result;
        if(empty($result[0])){
            throw new \Exception('Ошибка авторизации');
        }

        $result = $result[0];
        $result->setToken();
        $result->save();
        return $result;
    }

    public function beforePersist()
    {
        $this->setPassword($this->getPasswordHash($this->getPassword()));
        parent::beforePersist();
    }

    public function getUserByToken( $token ){
        /** @var \Doctrine\ORM\EntityManager $entManager */
        $em = App::getDoctrineEntityManager();
        /** @var QueryBuilder $qb */
        $qb = $em->createQueryBuilder();
        $result = $qb->select(['u'])
            ->from(get_class($this), 'u')
            ->where('u.token = :token')
            ->setParameter('token', $token)
            ->getQuery()->getResult();
        if(empty($result[0])){
            return null;
        }

        return $result[0];
    }
}