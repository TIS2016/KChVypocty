<?php
namespace App;
//require_once '../vendor/autoload.php';
use App\Db\Logs;

/**
 * Class Interactor
 * @package App
 *
 * Provides data for views
 */
class Interactor {

    const FILE_WITH_DIRECTORIES = "/home/tis/KChVypocty/app/crawl_dirs";

    private $formattedErrorMessages;

    public function __construct() {
        $this->formattedErrorMessages = "";
    }

    public function runParser(){

        $crawler = new \App\Crawler(Interactor::FILE_WITH_DIRECTORIES);
        $crawler->find();
        $files = $crawler->getPaths();

        foreach ($files as $file){
            $lexer = new Lexer($file);
            $calculations = $lexer->getCalculations();

            if (empty($calculations)) {
                $this->formattedErrorMessages .= $lexer->getErrorMessage() ."\n";
            } else {
                $parser = new Parser($calculations);
                $parser->setSpecialCharacter($lexer->getSpecialCharacter());
                $parser->setPath($lexer->getPath());
                $parser->setFile($lexer->getFile());
                $parser->parseCalculations();
            }
        }
    }

    public function hasErrors(){
        return !empty($this->formattedErrorMessages);
    }

    public function getErrors(){
        return $this->formattedErrorMessages;
    }

    public function saveReportToDb(){
        $entityManager = DoctrineSetup::getEntityManager();
        $log = new Logs();
        $log->setLogText($this->formattedErrorMessages);
        $entityManager->persist($log);
        $entityManager->flush();
        //echo "report successfuly saved to database";
    }

    public function changeStateToRunning() {
        $entityManager = DoctrineSetup::getEntityManager();
        $status = $entityManager->find("App\Db\Status", 1);
        $status->setStatus("running");
        $entityManager->flush();
    }

    public function changeStateToNotRunning(){
        $entityManager = DoctrineSetup::getEntityManager();
        $status = $entityManager->find("App\Db\Status", 1);
        $status->setStatus("notrunning");
        $entityManager->flush();

    }
    public function isRunning(){
        $entityManager = DoctrineSetup::getEntityManager();
        $status = $entityManager->find("App\Db\Status", 1);
        return $status->getStatus() == "running";
    }
}