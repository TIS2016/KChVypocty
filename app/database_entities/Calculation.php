<?php

namespace App\Db;
/**
 * @Entity @Table(name="calculations")
 *
 **/
class Calculation
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $calculationID;

    /** @Column(type="string") **/
    protected $jobType;

    /** @Column(type="string") **/
    protected $method;

    /** @Column(type="string") **/
    protected $basisSet;

    /** @Column(type="string") **/
    protected $stechiometry;

    /** @Column(type="string") **/
    protected $user;

    /**
     * @return mixed
     */
    public function getCalculationID()
    {
        return $this->calculationID;
    }

    /**
     * @param mixed $calculationID
     */
    public function setCalculationID($calculationID)
    {
        $this->calculationID = $calculationID;
    }

    /**
     * @return mixed
     */
    public function getJobType()
    {
        return $this->jobType;
    }

    /**
     * @param mixed $jobType
     */
    public function setJobType($jobType)
    {
        $this->jobType = $jobType;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getBasisSet()
    {
        return $this->basisSet;
    }

    /**
     * @param mixed $basisSet
     */
    public function setBasisSet($basisSet)
    {
        $this->basisSet = $basisSet;
    }

    /**
     * @return mixed
     */
    public function getStechiometry()
    {
        return $this->stechiometry;
    }

    /**
     * @param mixed $stechiometry
     */
    public function setStechiometry($stechiometry)
    {
        $this->stechiometry = $stechiometry;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param mixed $server
     */
    public function setServer($server)
    {
        $this->server = $server;
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

    /**
     * @return mixed
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @param mixed $coordinates
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
    }

    /** @Column(type="date") **/
    protected $date;

    /** @Column(type="string") **/
    protected $server;

    /** @Column(type="string") **/
    protected $path;


    /**
     * @OneToMany(targetEntity="Coordinate", mappedBy="calculation")
     */
    private $coordinates;


    public function __construct(){
        $this->coordinates = new \Doctrine\Common\Collections\ArrayCollection();
    }


}