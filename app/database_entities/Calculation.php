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


    public function getCalculationID(){
        return $this->calculationID;
    }

    public function setCalculationID($calculationID){
        $this->calculationID = $calculationID;
    }

    public function getJobType(){
        return $this->jobType;
    }

    public function setJobType($jobType){
        $this->jobType = $jobType;
    }

    public function getMethod(){
        return $this->method;
    }

    public function setMethod($method){
        $this->method = $method;
    }

    public function getBasisSet(){
        return $this->basisSet;
    }

    public function setBasisSet($basisSet){
        $this->basisSet = $basisSet;
    }

    public function getStechiometry(){
        return $this->stechiometry;
    }

    public function setStechiometry($stechiometry){
        $this->stechiometry = $stechiometry;
    }

    public function getUser(){
        return $this->user;
    }

    public function setUser($user){
        $this->user = $user;
    }

    public function getDate(){
        return $this->date;
    }

    public function setDate($date){
        $this->date = $date;
    }

    public function getServer(){
        return $this->server;
    }

    public function setServer($server){
        $this->server = $server;
    }

    public function getPath(){
        return $this->path;
    }

    public function setPath($path){
        $this->path = $path;
    }

    public function getCoordinates(){
        return $this->coordinates;
    }

    public function getCoordinatesFormat(){
        $coordinates = "";
        foreach ($this->coordinates as $coordinate) {
            $coordinates .=
                $coordinate->getAtom() . "          "
                . $coordinate->getX() . "          "
                . $coordinate->getY() . "          "
                . $coordinate->getZ() . "<br>";
        }
        return $coordinates;
    }

    public function getCoordinatesAsArray(){
        $coordinatesArray = [];
        foreach ($this->coordinates as $coordinate){
            $atom = [];
            $atom['atom'] = $coordinate->getAtom();
            $atom['y'] = $coordinate->getY();
            $atom['x'] = $coordinate->getX();
            $atom['z'] = $coordinate->getZ();
            $coordinatesArray[] = $atom;
        }
        return $coordinatesArray;
    }

    public function setCoordinates($coordinates){
        $this->coordinates = $coordinates;
    }


    public function getInfoInput(){
        return $this->infoInput;
    }

    public function setInfoInput($infoInput){
        $this->infoInput = $infoInput;
    }

    public function getInfoEnd(){
        return $this->infoEnd;
    }

    public function setInfoEnd($infoEnd){
        $this->infoEnd = $infoEnd;
    }

    public function getEnergy(){
        return $this->energy;
    }

    public function setEnergy($energy){
        $this->energy = $energy;
    }

    public function getThermoChemistry(){
        return $this->thermoChemistry;
    }

    public function setThermoChemistry($thermoChemistry){
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