<?php
namespace App;
session_start();
require_once '../../vendor/autoload.php';
$_SESSION['running'] = true;


$interactor = new Interactor();
$interactor->runParser();
$interactor->saveReportToDb();

if ($interactor->hasErrors()) {
    echo "Finished with errors";
} else {
    echo "Finished without errors";
}

unset($_SESSION['running']);
