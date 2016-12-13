<?php

namespace App;

use App\Db\Calculation;
use App\Db\Coordinate;
use App\Db\History;

class Parser {
    private $calculations;
    private $specialCharacter;
    private $replaceCharacter;
    private $path;
    private $file;
    private $parserJobTypesExtra;
    private $parserMethodsExtra;
    private $newLine;

    public function __construct($calculations) {
        $this->calculations = $calculations;
        $this->replaceCharacter = "$";
        $this->parserJobTypesExtra = new ParserJobTypesExtra();
        $this->parserMethodsExtra = new ParserMethodsExtra();
    }

    public function setSpecialCharacter($specialCharacter) {
        $this->specialCharacter = $specialCharacter;
    }

    public function setPath($path) {
        $this->path = $path;
        $this->setNewLine();
    }

    public function setFile($file) {
        $this->file = $file;
    }

    private function setNewLine() {
        $extension = pathinfo($this->path, PATHINFO_EXTENSION);
        if ($extension === Lexer::LINUX_FILE_EXTENSION) {
            $this->newLine = "\n";
        } else {
            $this->newLine = "\r\n";
        }
    }

    public function parseCalculations() {
        foreach($this->calculations as $calculation) {
            $this->parseCalculation($calculation);
        }
    }

    private function parseCalculation($calculation) {
        $calculation = $this->removeNewlines($calculation);
        $head = $this->parseHead($calculation);
        $calculation = $head[0];
        $tokenArray = $head[1];
        $calculation = $this->replaceDuplicities($calculation);
        $body = $this->parseBody($calculation);
        $calculation = $body[0];
        $parseBody = $body[1];

        $coordinates = $this->parseCoordinates($calculation, $tokenArray[1]);
        $calculation = $coordinates[0];
        $parseCoordinates = $coordinates[1];
        $energy = "";
        $thermoChemistry = "";

        if ($tokenArray[1] == $this->parserJobTypesExtra->getFreq()) {
            $parseTail = $this->parseFreqTail($calculation);
            $thermoChemistry = $this->parseTermochemistry();
        } else {
            $parseTail = $this->parseTail($calculation);
        }

        if ($this->parserMethodsExtra->isGMethod($tokenArray[2])) {
            $energy = $this->parseEnergy($tokenArray);
        }
        $this->insert($tokenArray, $parseBody, $parseCoordinates, $parseTail, $energy, $thermoChemistry);
    }

    private function insert($tokenArray, $body, $coordinates, $tail, $energy, $thermoChemistry) {
        $calculationDatabase = new Calculation();
        $calculationDatabase->setServer($tokenArray[0]);
        $calculationDatabase->setJobType($tokenArray[1]);
        $calculationDatabase->setMethod($tokenArray[2]);
        $calculationDatabase->setBasisSet($tokenArray[3]);
        $calculationDatabase->setStechiometry($tokenArray[4]);
        $calculationDatabase->setUser($tokenArray[5]);
        $calculationDatabase->setDate($tokenArray[6]);
        $calculationDatabase->setInfoInput($body);
        $calculationDatabase->setInfoEnd($tail);
        $calculationDatabase->setPath($this->path);
        $calculationDatabase->setEnergy($energy);
        $calculationDatabase->setThermoChemistry($thermoChemistry);

        DoctrineSetup::getEntityManager()->persist($calculationDatabase);
        DoctrineSetup::getEntityManager()->flush();

        $coordinatesDatabase = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($coordinates as $coordinate) {
            $coordinateDatabase = new Coordinate();
            $coordinateDatabase->setAtom($coordinate[0]);
            $coordinateDatabase->setX(floatval($coordinate[1]));
            $coordinateDatabase->setY(floatval($coordinate[2]));
            $coordinateDatabase->setZ(floatval($coordinate[3]));
            $coordinateDatabase->setCalculation($calculationDatabase);
            $coordinatesDatabase->add($coordinateDatabase);
        }
        $calculationDatabase->setCoordinates($coordinatesDatabase);
        DoctrineSetup::getEntityManager()->persist($calculationDatabase);
        DoctrineSetup::getEntityManager()->flush();

        $history = new History();
        $history->setPath($this->path);

        DoctrineSetup::getEntityManager()->persist($history);
        DoctrineSetup::getEntityManager()->flush();
    }

    private function parseHead($calculation) {
        $startSequence = '1' . $this->specialCharacter . '1' . $this->specialCharacter;
        $endSequence = $this->specialCharacter . $this->specialCharacter . '#';
        $startIndex = strpos($calculation, $startSequence) + strlen($startSequence);
        $endIndex = strpos($calculation, $endSequence);
        $head = substr($calculation, $startIndex, $endIndex-strlen($endSequence));
        $calculation = substr($calculation, $endIndex+strlen($endSequence), strlen($calculation));
        $tokenArray = array();
        while (strlen($head) > 0){
            $startIndex = 0;
            $endIndex = strpos($head, $this->specialCharacter);
            $token = substr($head, $startIndex, $endIndex);
            $head = substr($head, $endIndex+1, strlen($head)-$endIndex);
            array_push($tokenArray, $token);
        }
        return array($calculation, $tokenArray);
    }

    private function parseBody($calculation) {
        $endIndex = strpos($calculation, $this->specialCharacter);
        $parseBody = substr($calculation, 0, $endIndex);
        $calculation = substr($calculation, $endIndex+1, strlen($calculation)-$endIndex);
        $parseBody = $this->addDuplicities($parseBody);
        return array($calculation, $parseBody);
    }

    private function parseCoordinates($calculation, $method) {
        $endIndex = strpos($calculation, "Version");
        $tempCoordinates = substr($calculation, 0, $endIndex-1);
        $calculation = substr($calculation, $endIndex, strlen($calculation));
        $parserCoordinates = new ParserCoordinates($tempCoordinates);
        $parserCoordinates->setFile($this->file);
        $parserCoordinates->setMethod($method);
        $parserCoordinates->setSpecialCharacter($this->specialCharacter);
        $coordinates = $parserCoordinates->parseCoordinates();
        return array ($calculation, $coordinates);
    }

    private function parseTail($calculation) {
        $endIndex = strpos($calculation, $this->replaceCharacter);
        $tail = substr($calculation, 0, $endIndex);
        return $tail;
    }

    private function parseFreqTail($calculation) {
        $startIndex = strpos($calculation, "NImag");
        $calculation = substr($calculation, $startIndex, strlen($calculation)-$startIndex);
        $endIndex = strpos($calculation, $this->replaceCharacter);
        $tail = substr($calculation, 0, $endIndex);
        return $tail;
    }

    private function parseEnergy($tokenArray) {
        $tokenString = implode($this->specialCharacter, $tokenArray);
        $endIndex = strpos($this->file, $tokenString);
        $subFile = substr($this->file, 0, $endIndex-5);
        $startIndex = strrpos($subFile, $this->newLine . $this->newLine);
        $energy = substr($subFile, $startIndex+strlen($this->newLine . $this->newLine), strlen($subFile));
        return $energy;
    }

    private function parseTermochemistry() {
        $startIndex = strpos($this->file, " Zero-point correction=");
        $subFile = substr($this->file, $startIndex, strlen($this->file)-$startIndex);
        $endIndex = strpos($subFile, $this->newLine . " " . $this->newLine);
        $thermoChemistry = substr($subFile, 0, $endIndex+strlen($this->newLine));
        return $thermoChemistry;
    }

    private function removeNewlines($calculation) {
        $calculation = str_replace("\n ", "", $calculation);
        $calculation = str_replace("\n", "", $calculation);
        $calculation = str_replace("\r ", "", $calculation);
        $calculation = str_replace("\r", "", $calculation);
        return $calculation;
    }

    private function replaceDuplicities($calculation) {
        $special = $this->specialCharacter . $this->specialCharacter;
        $calculation = str_replace($special, $this->replaceCharacter, $calculation);
        return $calculation;
    }

    private function addDuplicities($calculation) {
        $special = $this->specialCharacter . $this->specialCharacter;
        $calculation = str_replace($this->replaceCharacter, $special, $calculation);
        return $calculation;
    }

}