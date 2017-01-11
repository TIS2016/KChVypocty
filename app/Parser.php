<?php

namespace App;

/**
 * Class Parser
 * @package App
 *
 * Parse required parts (calculations) and insert them into database
 *
 */
class Parser {
    private $calculations;
    private $specialCharacter;
    private $replaceCharacter;
    private $path;
    private $file;
    private $parserJobTypesExtra;
    private $parserMethodsExtra;
    private $newLine;
    private $calculationResults;
		
		/**
     * Parser constructor.
     * @param $calculations
     *
     * Constructor sets up parts of file (calculations) that will be parsed
     *
     */
    public function __construct($calculations) {
        $this->calculations = $calculations;
        $this->replaceCharacter = "$";
        $this->parserJobTypesExtra = new ParserJobTypesExtra();
        $this->parserMethodsExtra = new ParserMethodsExtra();
        $this->calculationResults = array();
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

		/**
     * Set newline depending on type of file (linux or windows)
     */
    private function setNewLine() {
        $extension = pathinfo($this->path, PATHINFO_EXTENSION);
        if ($extension === Lexer::LINUX_FILE_EXTENSION) {
            $this->newLine = "\n";
        } else {
            $this->newLine = "\r\n";
        }
    }

		/**
     * Parse all calculations from file, if used method is one of GMethods, insert only last calculation into database, otherwise insert all
     */
    public function parseCalculations() {
        foreach($this->calculations as $calculation) {
            $this->parseCalculation($calculation);
        }
        $calculationResult = $this->calculationResults[count($this->calculationResults)-1];
        $calculationResultMethod = $calculationResult->getMethod();
        if ($this->parserMethodsExtra->isGMethod($calculationResultMethod)) {
            $calculationResult->insert();
        } else {
            foreach ($this->calculationResults as $calculationResult) {
                $calculationResult->insert();
            }
        }
    }

		/**
     * @param $calculation 
		 * Parse one calculation, first row (heading), info from input, coordinates, energy, thermochemistry (if exists), 
		 * info from end and add to global array $calculationResults
     */
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
        $thermoChemistry = "";

        if ($tokenArray[1] == $this->parserJobTypesExtra->getFreq()) {
            $energyTail = $this->parseTail($calculation);
            $parseTail = $this->parseFreqTail($calculation);
            $thermoChemistry = $this->parseTermochemistry();
        } else {
            $parseTail = $this->parseTail($calculation);
        }

        if ($this->parserMethodsExtra->isGMethod($tokenArray[2])) {
            $energy = $this->parseEnergyGMethods($tokenArray);
        } else if ($this->parserMethodsExtra->isOVGFPartOfMethod($tokenArray[2])){
            $energy = $this->parseEnergyOVGF();
        } else if ($tokenArray[1] == $this->parserJobTypesExtra->getFreq()) {
            $energyArray = $this->parseEnergy($energyTail);
            $energy = $energyArray[0];
        } else {
            $energyArray = $this->parseEnergy($parseTail);
            $energy = $energyArray[0];
            $parseTail = $energyArray[1];
        }

        $calculationResult = new CalculationResult(
            $tokenArray,
            $parseBody,
            $parseCoordinates,
            $parseTail,
            $this->path,
            $energy,
            $thermoChemistry
        );
        $this->calculationResults[] = $calculationResult;
    }

		/**
		 * @param $calculation
		 * @return array
     * Parse first row from calculation, job type, method, basis set, stechiometry, user, date and server
     */
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
            $tokenArray[] = $token;
        }
        return array($calculation, $tokenArray);
    }

		/**
		 * @param $calculation
		 * @return array
     * Parse info from input from calculation
     */
    private function parseBody($calculation) {
        $endIndex = strpos($calculation, $this->specialCharacter);
        $parseBody = substr($calculation, 0, $endIndex);
        $calculation = substr($calculation, $endIndex+1, strlen($calculation)-$endIndex);
        $parseBody = str_replace($this->replaceCharacter, "<br>", $parseBody);
        return array($calculation, $parseBody);
    }

		/**
		 * @param $calculation
		 * @return array
     * Parse coordinates in class ParserCoordinates and return correct coordinates
     */
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

		/**
		 * @param $calculation
		 * @return string
     * Parse info from end of calculation
     */
    private function parseTail($calculation) {
        $endIndex = strpos($calculation, $this->replaceCharacter);
        $tail = substr($calculation, 0, $endIndex);
        $tailArray = explode($this->specialCharacter, $tail);
        $tail = implode("<br>", $tailArray);
        return $tail;
    }

		/**
		 * @param $calculation
		 * @return string
     * Parse info from end of calculation if job type is Freq
     */
    private function parseFreqTail($calculation) {
        $startIndex = strpos($calculation, "NImag");
        $calculation = substr($calculation, $startIndex, strlen($calculation)-$startIndex);
        $endIndex = strpos($calculation, $this->replaceCharacter);
        $tail = substr($calculation, 0, $endIndex);
        return $tail;
    }

		/**
		 * @param $tokenArray
		 * @return string
     * Parse energy if method is one of GMethods
     */
    private function parseEnergyGMethods($tokenArray) {
        $tokenString = implode($this->specialCharacter, $tokenArray);
        $endIndex = strpos($this->file, $tokenString);
        $subFile = substr($this->file, 0, $endIndex-5);
        $startIndex = strrpos($subFile, $this->newLine . $this->newLine);
        $energy = substr($subFile, $startIndex+strlen($this->newLine . $this->newLine)-1, strlen($subFile));
        $energy = $this->formatSpacesForNewlines($energy);
        return $energy;
    }

		/**
		 * @return string
     * Parse energy if method is OVGF, energy is in whole file, not in calculation
     */
    private function parseEnergyOVGF() {
        $allEnergy = "";
        $startSequence = "Summary of results for alpha spin-orbital";
        $startIndex = strpos($this->file, $startSequence);
        $subFile = substr($this->file, $startIndex, strlen($this->file)-$startIndex);
        $endIndex = strpos($subFile, $this->newLine . $this->newLine);
        $energy = substr($subFile, 0, $endIndex);
        $subFile = substr($subFile, $endIndex, strlen($subFile)-$endIndex);
        $energy = str_replace($this->newLine, "<br>", $energy);
        $energy = preg_replace("/\s\s+/", " ",$energy);

        $allEnergy .= $energy . "<br><br>";

        while ($startIndex != false && $endIndex != false) {
            $startIndex = strpos($subFile, $startSequence);
            $subFile = substr($subFile, $startIndex, strlen($subFile)-$startIndex);
            $endIndex = strpos($subFile, $this->newLine . $this->newLine);
            $energy = substr($subFile, 0, $endIndex);
            $subFile = substr($subFile, $endIndex, strlen($subFile)-$endIndex);
            $energy = str_replace($this->newLine, "<br>", $energy);
            $energy = preg_replace("/\s\s+/", " ",$energy);
            $allEnergy .= $energy . "<br><br>";
        }

        $allEnergy = substr($allEnergy, 0, strlen($allEnergy)-12);
        return $allEnergy;
    }

		/**
		 * @param $tail
		 * @return array
     * Parse default energy from tail of calculation
     */
    private function parseEnergy($tail) {
        $startSequence = "State";
        $endSequence = "RMSD";
        $startIndex = strpos($tail, $startSequence);
        if ($startIndex  == false) {
            $startSequence = "Version";
            $startIndex = strpos($tail, $startSequence);
        }
        $endIndex = strpos($tail, $endSequence);
        $energy = substr($tail, $startIndex, $endIndex - $startIndex);
        $energyArray = explode("<br>", $energy);
        array_shift($energyArray);
        $energy = implode("<br>", $energyArray);
        $startIndex = strpos($tail, $energy);
        $tail = substr($tail, 0, $startIndex)
            . substr($tail, $startIndex+strlen($energy), strlen($tail)-$startIndex-strlen($energy));
        return array($energy, $tail);
    }

		/**
		 * @return string
     * Parse thermochemistry from whole file, thermochemistry is not in calculation
     */
    private function parseTermochemistry() {
        $startIndex = strpos($this->file, " Zero-point correction=");
        $subFile = substr($this->file, $startIndex, strlen($this->file)-$startIndex);
        $endIndex = strpos($subFile, $this->newLine . " " . $this->newLine);
        $termoChemistry = substr($subFile, 0, $endIndex+strlen($this->newLine));
        $termoChemistry = $this->formatSpacesForNewlines($termoChemistry);
        return $termoChemistry;
    }

		/**
		 * @param $string
		 * @return string
     * Help method for formatting spaces and newlines
     */
    private function formatSpacesForNewlines($string) {
        $string = str_replace($this->newLine, "", $string);
        $string = preg_replace("/\s\s+/", "",$string);
        $string = preg_replace("/(\d{2,})\s+/", "$1<br>", $string);
        return $string;
    }

    private function removeNewlines($calculation) {
        $calculation = str_replace("\n ", "", $calculation);
        $calculation = str_replace("\n", "", $calculation);
        $calculation = str_replace("\r ", "", $calculation);
        $calculation = str_replace("\r", "", $calculation);
        return $calculation;
    }

		/**
		 * @param $calculation
		 * @return string
     * Replace all special characters (it depends on type of file -> linux or windows) with $replaceCharacter
     */
    private function replaceDuplicities($calculation) {
        $special = $this->specialCharacter . $this->specialCharacter;
        $calculation = str_replace($special, $this->replaceCharacter, $calculation);
        return $calculation;
    }

}
