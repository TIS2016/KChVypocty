<?php

namespace App;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Crawler {
    private $directory;
    private $fileExtension;
    private $filePaths = array();

    public function __construct($configJsonFileName) {
        $rawJsonConfiguration = file_get_contents ($configJsonFileName);
        $parsedConfiguration = json_decode($rawJsonConfiguration, true);

        $this->directory = $parsedConfiguration["crawler"]["dir_name"];
        $this->fileExtension = $parsedConfiguration["crawler"]["file_end_name"];
    }

    public function find()
    {
        $directoryIterator = new RecursiveDirectoryIterator($this->directory);
        foreach (new RecursiveIteratorIterator($directoryIterator) as $filename => $file) {
            preg_match("/".$this->fileExtension."/", $file, $out);
            if (sizeof($out) != 0) {
                array_push($this->filePaths, basename($file));
            }
        }
    }

    public function printArray()
    {
        print_r($this->filePaths);
    }
}















