<?php
namespace App\Db;

/**
 * @Entity @Table(name="users")
 *
 **/

class User {

    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $userId;

    /** @Column(type="string") **/
    protected $login;

    /** @Column(type="string") **/
    protected $password;
}