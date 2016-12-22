<?php

namespace App;


class ParserMethodsExtra {
    private $gMethods;
    private $OVGF;

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

    public function isGMethod($method) {
        return in_array($method, $this->gMethods);
    }

    public function isOVGFPartOfMethod($method) {
        return strpos($method, $this->OVGF) !== false;
    }

}