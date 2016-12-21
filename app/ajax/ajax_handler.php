<?php
namespace App;
require_once '../../vendor/autoload.php';

$interactor = new Interactor();
$interactor->runParser();
//$interactor->saveReportToDb();

if ($interactor->hasErrors()) {
    echo "Finished with errors";
} else {
    echo "Finished without errors";
}
