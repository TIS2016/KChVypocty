<?php
use App\Lexer;
use App\Parser;

require_once __DIR__ . '/vendor/autoload.php';
turnOnDisplayErrors();
function turnOnDisplayErrors()
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}


$crawler = new \App\Crawler("app/crawl_dirs");
$crawler->find();

$files = $crawler->getPaths();

foreach ($files as $file){
    $lexer = new Lexer($file);
    $calculations = $lexer->getCalculations();

    if (empty($calculations)) {
        echo $lexer->getErrorMessage();
    } else {
        $parser = new Parser($calculations);
        $parser->setSpecialCharacter($lexer->getSpecialCharacter());
        $parser->setPath($lexer->getPath());
        $parser->setFile($lexer->getFile());
        $parser->parseCalculations();
    }
}


