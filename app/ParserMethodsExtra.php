<?php

namespace App;

/**
 * Class ParserMethodsExtra
 * @package App
 *
 * Class for different methods used in calculations
 *
 */
class ParserMethodsExtra {
    private $gMethods;
    private $OVGF;

		/**
     * ParserMethodsExtra constructor.
     *
     * Constructor sets up all GMethods and OVGF method
     *
     */
    public function __construct() {
        $this->gMethods = array();
        array_push($this->gMethods, "G1");
        array_push($this->gMethods, "G2");
        array_push($this->gMethods, "G2MP2");
        array_push($this->gMethods, "G3");
        array_push($this->gMethods, "G3MP2");
        array_push($this->gMethods, "G3B3");
        array_push($this->gMethods, "G3MP2B3");
        array_push($this->gMethods, "G4");
        array_push($this->gMethods, "G4MP2");
        $this->OVGF = "OVGF";
    }

		/**
		 * @param $method
		 * @return boolean
     * Returns if $method is one of GMethods
     */
    public function isGMethod($method) {
        return in_array($method, $this->gMethods);
    }

		/**
		 * @param $method
		 * @return boolean
     * Returns if $method is part of OVGF method
     */
    public function isOVGFPartOfMethod($method) {
        return strpos($method, $this->OVGF) !== false;
    }

}
