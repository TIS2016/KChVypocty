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

    /**
     * @return mixed
     */
    public function getHistoryID()
    {
        return $this->historyID;
    }

    /**
     * @param mixed $calculationID
     */
    public function setHistoryID($historyID)
    {
        $this->historyID = $historyID;
    }

    /**
     * @return mixed
     */

    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }


}