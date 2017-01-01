<?php
namespace App\Db;

/**
 * @Entity @Table(name="users")
 *
 **/

class User {

    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $userId;

    public function getUserId(){
        return $this->userId;
    }

    public function setUserId($userId){
        $this->userId = $userId;
    }

    public function getLogin(){
        return $this->login;
    }

    public function setLogin($login){
        $this->login = $login;
    }

    public function getPassword(){
        return $this->password;
    }

    public function setPassword($password){
        $this->password = $password;
    }

    /** @Column(type="string") **/
    protected $login;

    /** @Column(type="string") **/
    protected $password;
}