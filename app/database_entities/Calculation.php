<?php

namespace App\Db;
/**
 * @Entity @Table(name="calculations")
 *
 **/
class Calculation {

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

    /** @Column(type="string") **/
    protected $date;

    /** @Column(type="string") **/
    protected $server;

    /** @Column(type="string") **/
    protected $path;

    /** @Column(type="text") **/
    protected $infoInput;

    /** @Column(type="text") **/
    protected $infoEnd;

    /** @Column(type="text") **/
    protected $energy;

    /** @Column(type="text") **/
    protected $thermoChemistry;


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


    /**
     * @return mixed
     */
    public function getInfoInput()
    {
        return $this->infoInput;
    }

    /**
     * @param mixed $infoInput
     */
    public function setInfoInput($infoInput)
    {
        $this->infoInput = $infoInput;
    }

    /**
     * @return mixed
     */
    public function getInfoEnd()
    {
        return $this->infoEnd;
    }

    /**
     * @param mixed $infoEnd
     */
    public function setInfoEnd($infoEnd)
    {
        $this->infoEnd = $infoEnd;
    }

    /**
     * @return mixed
     */
    public function getEnergy()
    {
        return $this->energy;
    }

    /**
     * @param mixed $energy
     */
    public function setEnergy($energy)
    {
        $this->energy = $energy;
    }

    /**
     * @return mixed
     */
    public function getThermoChemistry()
    {
        return $this->thermoChemistry;
    }

    /**
     * @param mixed $thermoChemistry
     */
    public function setThermoChemistry($thermoChemistry)
    {
        $this->thermoChemistry = $thermoChemistry;
    }


    /**
     * @OneToMany(targetEntity="Coordinate", mappedBy="calculation", cascade="persist")
     */
    private $coordinates;



    public function __construct(){
        $this->coordinates = new \Doctrine\Common\Collections\ArrayCollection();
    }



}