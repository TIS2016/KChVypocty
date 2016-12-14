<?php
namespace App;
require_once '../../vendor/autoload.php';

$interactor = new Interactor();
$interactor->runParser();

echo "OK";
