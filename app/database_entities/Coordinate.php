<?php

namespace App\Db;

/**
 * @Entity @Table(name="coordinates")
 *
 **/

class Coordinate {

    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $pointCoordinateId;

    /** @Column(type="string") **/
    protected $atom;

    /** @Column(type="float") **/
    protected $x;

    /** @Column(type="float") **/
    protected $y;

    /** @Column(type="float") **/
    protected $z;

    public function getPointCoordinateId(){
        return $this->pointCoordinateId;
    }

    public function setPointCoordinateId($pointCoordinateId){
        $this->pointCoordinateId = $pointCoordinateId;
    }

    public function getAtom(){
        return $this->atom;
    }

    public function setAtom($atom){
        $this->atom = $atom;
    }

    public function getX(){
        return $this->x;
    }

    public function setX($x){
        $this->x = $x;
    }

    public function getY(){
        return $this->y;
    }

    public function setY($y){
        $this->y = $y;
    }

    public function getZ(){
        return $this->z;
    }

    public function setZ($z){
        $this->z = $z;
    }

    public function getCalculation(){
        return $this->calculation;
    }

    public function setCalculation($calculation){
        $this->calculation = $calculation;
    }


    /**
     * @ManyToOne(targetEntity="Calculation", inversedBy="coordinates")
     * @JoinColumn(name="calculation_id", referencedColumnName="calculationID")
     */
    private $calculation;
}