<?php

namespace App\Db;
/**
 * @Entity @Table(name="logs")
 *
 **/
class Logs {

    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $logID;

    /** @Column(type="text") **/
    protected $logText;

    public function getLogID(){
        return $this->logID;
    }

    public function setLogID($logID){
        $this->logID = $logID;
    }

    public function getLogText(){
        return $this->logText;
    }

    public function setLogText($logText){
        $this->logText = $logText;
    }


}