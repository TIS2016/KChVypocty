<?php

namespace App\Db;
/**
 * @Entity @Table(name="history")
 *
 **/
class History {

    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $historyID;

    /** @Column(type="string") **/
    protected $path;

    public function getHistoryID(){
        return $this->historyID;
    }

    public function setHistoryID($historyID){
        $this->historyID = $historyID;
    }

    public function getPath(){
        return $this->path;
    }

    public function setPath($path){
        $this->path = $path;
    }
}