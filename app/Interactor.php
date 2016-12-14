<?php
namespace App;
class Interactor {

    const FILE_WITH_DIRECTORIES = "/home/tis/KChVypocty/app/crawl_dirs";

    public function runParser(){

        $crawler = new \App\Crawler(Interactor::FILE_WITH_DIRECTORIES);
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

    }


}