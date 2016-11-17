<?php


/**
 * @Entity @Table(name="coordinates")
 *
 **/

class Coordinate {

    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $pointCoordinateId;

    /** @Column(type="decimal") **/
    protected $x;

    /** @Column(type="decimal") **/
    protected $y;

    /**
     * @ManyToOne(targetEntity="Calculation", inversedBy="coordinates")
     * @JoinColumn(name="calculation_id", referencedColumnName="productId")
     */
    private $calculation;
}