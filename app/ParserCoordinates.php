<?php

namespace App;


class ParserCoordinates {
    private $coordinates;
    private $method;
    private $file;
    private $specialCharacter;
    private $parserJobTypesExtra;
    private $periodicTable;

    public function __construct($coordinates) {
        $this->coordinates = $coordinates;
        $this->parserJobTypesExtra = new ParserJobTypesExtra();
        $this->fillPeriodicTable();
    }

    public function setMethod($method) {
        $this->method = $method;
    }

    public function setFile($file) {
        $this->file = $file;
    }

    public function setSpecialCharacter($specialCharacter) {
        $this->specialCharacter = $specialCharacter;
    }

    private function fillPeriodicTable() {
        $this->periodicTable = array(
          "X","H","He","Li","Be","B","C","N","O","F","Ne","Na","Mg","Al","Si","P",
          "S","Cl","Ar","K","Ca","Sc","Ti","V","Cr","Mn","Fe","Co","Ni","Cu","Zn",
          "Ga","Ge","As","Se","Br","Kr","Rb","Sr","Y","Zr","Nb","Mo","Tc","Ru","Rh",
          "Pd","Ag","Cd","In","Sn","Sb","Te","I","Xe","Cs","Ba","La","Ce","Pr","Nd",
          "Pm","Sm","Eu","Gd","Tb","Dy","Ho","Er","Tm","Yb","Lu","Hf","Ta","W","Re",
          "Os","Ir","Pt","Au","Hg","Tl","Pb","Bi","Po","At","Rn","Fr","Ra","Ac","Th",
          "Pa","U","Np","Pu","Am","Cm","Bk","Cf","Es","Fm","Md","No","Lr","Rf","Db",
          "Sg","Bh","Hs","Mt","Uun","Uuu"
        );
    }

    private function getAtomFromPeriodicTable($value) {
        if ($value == -1) {
            $value = 0;
        }
        return $this->periodicTable[$value];
    }

    public function parseCoordinates() {
        if ($this->method === $this->parserJobTypesExtra->getScan()) {
            $matrix = $this->getStandardOrientation();
            $this->parseZMatrixCoordinates($matrix);
        } else {
            $endIndex = strpos($this->coordinates, $this->specialCharacter);
            $difference = substr($this->coordinates, 0, $endIndex);
            if (strpos($difference, '.') !== false) {
                $this->parseNormalCoordinates();
            } else {
                $matrix = $this->getZMatrixOrientation();
                $this->parseZMatrixCoordinates($matrix);
            }
        }
        return $this->coordinates;
    }

    private function parseNormalCoordinates() {
        $tempCoordinates = $this->coordinates . $this->specialCharacter;
        $coordinates = array();
        while(strlen($tempCoordinates) > 0) {
            $index = strpos($tempCoordinates, $this->specialCharacter);
            $coordinate = substr($tempCoordinates, 0, $index);
            array_push($coordinates, $coordinate);
            $tempCoordinates = substr($tempCoordinates, $index + 1, strlen($tempCoordinates)-$index);
        }
        $this->coordinates = $this->splitCoordinates($coordinates);
    }


    private function parseZMatrixCoordinates($matrix) {
        $matrix = $this->removeNewlines($matrix);
        $matrix = explode(" ", $matrix);
        $coord = array();
        foreach ($matrix as $m) {
            if ($m != "") {
                array_push($coord, $m);
            }
        }
        $this->coordinates = array();
        $coordinate = array();
        for ($i = 0; $i < count($coord); $i++) {
            if ($this->filterAtom($i)) {
                $coord[$i] = $this->getAtomFromPeriodicTable($coord[$i]);
            }
            if ($this->filterUnnecessary($i)) {
                array_push($coordinate, $coord[$i]);
            }
        }

        for ($i = 3; $i < count($coordinate); $i++) {
            if (($i+1)%4 == 0) {
                $row = array(
                    $coordinate[$i-3],
                    $coordinate[$i-2],
                    $coordinate[$i-1],
                    $coordinate[$i]
                );
                array_push($this->coordinates, $row);
            }
        }
    }

    private function filterUnnecessary($i){
        return ($i % 6 != 0) && (($i - 2) % 6 != 0);
    }

    private function filterAtom($i) {
        return ($i - 1) % 6 == 0;
    }

    private function getZMatrixOrientation() {
        $startIndex = strrpos($this->file, "Z-Matrix orientation:");
        $zMatrix = substr($this->file, $startIndex, strlen($this->file));
        $delimiter = "---------------------------------------------------------------------";
        $count = 3;
        $zMatrixArray = array();

        while ($count > 0) {
            $index = strpos($zMatrix, $delimiter);
            $zMatrix = substr($zMatrix, $index+strlen($delimiter), strlen($zMatrix));
            $index = strpos($zMatrix, $delimiter);
            if ($count > 1) {
                $line = substr($zMatrix, 0, $index);
                array_push($zMatrixArray, $line);
            }
            $count--;
        }
        return $zMatrixArray[1];
    }

    private function getStandardOrientation() {
        $startIndex = strpos($this->file, "Standard orientation:");
        $zMatrix = substr($this->file, $startIndex, strlen($this->file));
        $delimiter = "---------------------------------------------------------------------";
        $count = 3;
        $zMatrixArray = array();

        while ($count > 0) {
            $index = strpos($zMatrix, $delimiter);
            $zMatrix = substr($zMatrix, $index+strlen($delimiter), strlen($zMatrix));
            $index = strpos($zMatrix, $delimiter);
            if ($count > 1) {
                $line = substr($zMatrix, 0, $index);
                array_push($zMatrixArray, $line);
            }
            $count--;
        }
        return $zMatrixArray[1];
    }

    private function splitCoordinates($coordinates) {
        $result = array();
        foreach($coordinates as $coordinate) {
            $coord = array();
            $split = explode(",", $coordinate);
            array_push($coord, $split[0]);
            foreach($split as $c) {
                if (strpos($c, ".")) {
                    array_push($coord, $c);
                }
            }
            array_push($result, $coord);
        }
        return $result;
    }

    private function removeNewlines($calculation) {
        $calculation = str_replace("\n ", "", $calculation);
        $calculation = str_replace("\n", "", $calculation);
        $calculation = str_replace("\r ", "", $calculation);
        $calculation = str_replace("\r", "", $calculation);
        return $calculation;
    }

}