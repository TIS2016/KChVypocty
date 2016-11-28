<?php

namespace App;


class Lexer {
    private $file;
    private $errorMessage;
    private $specialCharacter;

    const LINUX_FILE_EXTENSION = "log";
    const WINDOWS_FILE_EXTENSION = "out";

    const LINUX_FILE_TYPE_CHARACTER = "\\";
    const WINDOWS_FILE_TYPE_CHARACTER = "|";

    public function __construct($file) {
        $this->file = $file;
        $this->setDefaultValues();
    }

    private function setDefaultValues(){
        $this->errorMessage = "";
        $this->specialCharacter = "";
    }

    private function isLinuxFile($extension) {
        return $extension === Lexer::LINUX_FILE_EXTENSION;
    }

    private function isWindowsFile($extension) {
        return $extension === Lexer::WINDOWS_FILE_EXTENSION;
    }

    public function getErrorMessage() {
        return $this->errorMessage;
    }

    private function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
    }

    public function hasErrors(){
        return $this->errorMessage != "";
    }

    private function isNotValidFileExtension() {
        $extension = pathinfo($this->file, PATHINFO_EXTENSION);
        if ($this->isLinuxFile($extension)){
            $this->specialCharacter = Lexer::LINUX_FILE_TYPE_CHARACTER;
            return false;
        } elseif ($this->isWindowsFile($extension)){
            $this->specialCharacter = Lexer::WINDOWS_FILE_TYPE_CHARACTER;
            return false;
        }
        return true;
    }


    public function readFile() {
        $fileContent = file_get_contents($this->file);
        $calculations = array();

        $startSequence = '1' . $this->specialCharacter . '1' . $this->specialCharacter;
        $endSequence = $this->specialCharacter . $this->specialCharacter . '@';
        $startIndex = strpos($fileContent, $startSequence);
        $endIndex = strpos($fileContent, $endSequence);

        while ($startIndex != false && $endIndex != false) {
            $calculation = substr($fileContent, $startIndex, $endIndex-$startIndex+strlen($endSequence)+1);
            array_push($calculations, $calculation);
            $fileContent = substr($fileContent, $endIndex + strlen($endSequence));
            $startIndex = strpos($fileContent, $startSequence);
            $endIndex = strpos($fileContent, $endSequence);
        }

        return $calculations;
    }

    public function getCalculations(){
        return $this->getCalculationsFromFile();
    }

    private function getCalculationsFromFile() {
        if ($this->isNotValidFileExtension()){
            $this->setErrorMessage("Invalid file extension!");
            return [];
        }

        if (!file_exists($this->file)){
            $this->setErrorMessage("File does not exists!");
            return [];
        }

        $calculationsData = $this->readFile();
        if (count($calculationsData) == 0) {
            $this->setErrorMessage("No data found!");
            return [];
        }

        return $calculationsData;
    }


}