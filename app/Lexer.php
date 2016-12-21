<?php

namespace App;


class Lexer {
    private $path;
    private $errorMessage;
    private $specialCharacter;
    private $file;

    const LINUX_FILE_EXTENSION = "log";
    const WINDOWS_FILE_EXTENSION = "out";

    const LINUX_FILE_TYPE_CHARACTER = "\\";
    const WINDOWS_FILE_TYPE_CHARACTER = "|";

    public function __construct($path) {
        $this->path = $path;
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
        $extension = pathinfo($this->path, PATHINFO_EXTENSION);
        if ($this->isLinuxFile($extension)){
            $this->specialCharacter = Lexer::LINUX_FILE_TYPE_CHARACTER;
            return false;
        } elseif ($this->isWindowsFile($extension)){
            $this->specialCharacter = Lexer::WINDOWS_FILE_TYPE_CHARACTER;
            return false;
        }
        return true;
    }

    private function alreadyExists() {
        $entityManager = \App\DoctrineSetup::getEntityManager();
        $dql = "SELECT history FROM \App\Db\History history WHERE history.path=?1";
        $history = $entityManager->createQuery($dql)
            ->setParameter(1, $this->path)
            ->getResult();
        return (count($history) > 0);
    }

    private function readFile() {
        $fileContent = file_get_contents($this->path);
        $this->file = $fileContent;
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

    public function getSpecialCharacter() {
        return $this->specialCharacter;
    }

    public function getPath() {
        return $this->path;
    }

    public function getFile() {
        return $this->file;
    }

    public function getCalculations(){
        return $this->getCalculationsFromFile();
    }

    private function getCalculationsFromFile() {
        if ($this->isNotValidFileExtension()){
            $this->setErrorMessage($this->getPath()." : Invalid file extension!");
            return [];
        }

        if (!file_exists($this->path)){
            $this->setErrorMessage($this->getPath()." : File does not exists!");
            return [];
        }

        if ($this->alreadyExists()) {
            $this->setErrorMessage($this->getPath()." : File is already processed in database.");
            return [];
        }

        $calculationsData = $this->readFile();
        if (count($calculationsData) == 0) {
            $this->setErrorMessage($this->getPath()." : No data found!");
            return [];
        }

        return $calculationsData;
    }

}