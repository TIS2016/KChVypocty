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

    /**
     * @return mixed
     */
    public function getPointCoordinateId()
    {
        return $this->pointCoordinateId;
    }

    /**
     * @param mixed $pointCoordinateId
     */
    public function setPointCoordinateId($pointCoordinateId)
    {
        $this->pointCoordinateId = $pointCoordinateId;
    }

    /**
     * @return mixed
     */
    public function getAtom()
    {
        return $this->atom;
    }

    /**
     * @param mixed $x
     */
    public function setAtom($atom)
    {
        $this->atom = $atom;
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return mixed
     */
    public function getZ()
    {
        return $this->z;
    }

    /**
     * @param mixed $y
     */
    public function setZ($z)
    {
        $this->z = $z;
    }


    /**
     * @return mixed
     */
    public function getCalculation()
    {
        return $this->calculation;
    }

    /**
     * @param mixed $calculation
     */
    public function setCalculation($calculation)
    {
        $this->calculation = $calculation;
    }


    /**
     * @ManyToOne(targetEntity="Calculation", inversedBy="coordinates")
     * @JoinColumn(name="calculation_id", referencedColumnName="calculationID")
     */
    private $calculation;
}