<?php
namespace App;
require_once '../../vendor/autoload.php';


$interactor = new Interactor();
$interactor->changeStateToRunning();
$interactor->runParser();
$interactor->saveReportToDb();
$interactor->changeStateToNotRunning();

if ($interactor->hasErrors()) {
    echo "Finished with errors";
} else {
    echo "Finished without errors";
}

