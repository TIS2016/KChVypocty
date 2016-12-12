<?php

namespace App;


class ParserJobTypesExtra {
    private $freq;
    private $scan;

    public function __construct() {
        $this->freq = "Freq";
        $this->scan = "Scan";
    }

    public function getFreq() {
        return $this->freq;
    }

    public function getScan() {
        return $this->scan;
    }

}