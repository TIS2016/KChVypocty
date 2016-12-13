<?php
require_once __DIR__ . '/vendor/autoload.php';
turnOnDisplayErrors();
function turnOnDisplayErrors()
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}


$crawler = new \App\Crawler("app/crawl_dirs");
$crawler->find();
var_dump($crawler->getPaths());

