<?php

namespace App;

/**
 * Class ParserJobTypesExtra
 * @package App
 *
 * Class for different job types used in calculations
 *
 */
class ParserJobTypesExtra {
    private $freq;
    private $scan;
		
		/**
     * ParserJobTypesExtra constructor.
     *
     * Constructor sets up all job types
     *
     */
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
