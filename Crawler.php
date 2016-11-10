<?php


class Crawler {

    private $dir;
    private $file_end;
    private $array = array();

    public function __construct($json_name) {
        $data = file_get_contents ($json_name);
        $json = json_decode($data, true);

        $this->dir = $json["crawler"]["dir_name"];
        $this->file_end = $json["crawler"]["file_end_name"];
    }

    public function find()
    {
        $di = new RecursiveDirectoryIterator($this->dir);
        foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
            preg_match("/".$this->file_end."/", $file, $out);
            if (sizeof($out) != 0) {
//                echo basename($file). "<br>";
                array_push($this->array, basename($file));
            }
        }
    }

    public function printArray()
    {
        print_r($this->array);
    }
}

$crawler = new Crawler("config.json");
$crawler->find();
$crawler->printArray();














