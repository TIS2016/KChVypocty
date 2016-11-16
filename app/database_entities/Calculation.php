<?php


/**
 * @Entity @Table(name="calculations")
 *
 **/
class Calculation
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $productId;

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