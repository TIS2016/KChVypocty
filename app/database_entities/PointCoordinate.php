<?php


/**
 * @Entity @Table(name="point_coordinates")
 *
 **/

class PointCoordinate {

    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $pointCoordinateId;

    /** @Column(type="numeric") **/
    protected $x;

    /** @Column(type="numeric") **/
    protected $y;
}