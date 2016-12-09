<?php

namespace App\Db;

/**
 * @Entity @Table(name="coordinates")
 *
 **/

class Coordinate {

    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $pointCoordinateId;

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

    /** @Column(type="decimal") **/
    protected $x;

    /** @Column(type="decimal") **/
    protected $y;

    /**
     * @ManyToOne(targetEntity="Calculation", inversedBy="coordinates")
     * @JoinColumn(name="calculation_id", referencedColumnName="calculationID")
     */
    private $calculation;
}