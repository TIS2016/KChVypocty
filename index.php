<?php
turnOnDisplayErrors();

require_once __DIR__ . '/vendor/autoload.php';
use App\Crawler;

$crawler = new Crawler('crawl_config.json');
echo "Test";
//$crawler->find();
//$crawler->printArray();

function turnOnDisplayErrors()
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}
