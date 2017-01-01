<?php
namespace App\Views;
use App\DoctrineSetup;

require_once '../../vendor/autoload.php';


$entityManager = DoctrineSetup::getEntityManager();
$dql = "SELECT logs FROM \App\Db\Logs logs ORDER BY logs.logID DESC LIMIT 1";
$logs = $entityManager->createQuery($dql)
    ->getResult();

var_dump($logs);
