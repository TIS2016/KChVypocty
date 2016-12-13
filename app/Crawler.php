<?php

namespace App;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Crawler {
    private $directories;
    private $filePaths = array();

    public function __construct($configJsonFileName) {
        $paths = $this->getPathsFromFile($configJsonFileName);
        $this->directories = $paths;
        $this->linuxFileExtension = ".log";
        $this->windowsFileExtension = ".out";
    }

    public function find() {
        foreach ($this->directories as $directory) {
            var_dump($directory);
            $directoryIterator = new RecursiveDirectoryIterator($directory);
            foreach (new RecursiveIteratorIterator($directoryIterator) as $filename => $file) {
                preg_match("/" . $this->windowsFileExtension . "/", $file, $out);
                if (sizeof($out) != 0) {
                    if (basename($file) != "." && basename($file) != "..") {
                        array_push($this->filePaths, $filename);
                    }
                }

                preg_match("/" . $this->windowsFileExtension . "/", $file, $out);
                if (sizeof($out) != 0) {
                    if (basename($file) != "." && basename($file) != "..") {
                        array_push($this->filePaths, $filename);
                    }
                }
            }
        }
    }

    public function getPaths() {
        return $this->filePaths;
    }

    private function getPathsFromFile($configJsonFileName) {
        $paths = file_get_contents($configJsonFileName);
        $paths = explode("\n", $paths);
        return $paths;
    }
}















