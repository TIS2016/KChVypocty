<?php

namespace App\Db;
/**
 * @Entity @Table(name="status")
 *
 **/
class Status {

    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $statusID;

    /** @Column(type="string") **/
    protected $status;

    public function getStatusId(){
        return $this->statusID;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setStatus($status){
        $this->status = $status;
    }
}