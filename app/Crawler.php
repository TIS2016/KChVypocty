<?php

namespace App;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class Crawler
 * @package App
 *
 * Recursively crawl directories and finds files paths for parser
 *
 */
class Crawler {
    private $directories;
    private $filePaths = array();

    /**
     * Crawler constructor.
     * @param $fileWithCrawlDirectories
     *
     * Constructor sets up crawler
     *
     */
    public function __construct($fileWithCrawlDirectories) {
        $paths = $this->getPathsFromFile($fileWithCrawlDirectories);
        $this->directories = $paths;
        $this->linuxFileExtension = ".log";
        $this->windowsFileExtension = ".out";
    }

    /**
     * Finds all *.log and *.out files on the server and push paths the array
     */
    public function find() {
        foreach ($this->directories as $directory) {
            $directoryIterator = new RecursiveDirectoryIterator($directory);
            foreach (new RecursiveIteratorIterator($directoryIterator) as $filename => $file) {
                preg_match("/" . $this->windowsFileExtension . "/", $file, $out);
                if (sizeof($out) != 0) {
                    if (basename($file) != "." && basename($file) != "..") {
                        array_push($this->filePaths, $filename);
                    }
                }

                preg_match("/" . $this->linuxFileExtension . "/", $file, $out);
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

    /**
     * @param $fileWithCrawlDirectories
     * @return array|string
     *
     * Reads all directories to crawl
     *
     */
    private function getPathsFromFile($fileWithCrawlDirectories) {
        $paths = file_get_contents($fileWithCrawlDirectories);
        $paths = explode("\n", $paths);
        return $paths;
    }
}















