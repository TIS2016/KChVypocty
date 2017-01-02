<?php
namespace App\Views;
use App\DoctrineSetup;
turnOnDisplayErrors();
function turnOnDisplayErrors()
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}
require_once '../../vendor/autoload.php';

$entityManager = DoctrineSetup::getEntityManager();
$dql = "SELECT logs FROM \App\Db\Logs logs ORDER BY logs.logID DESC";
$logs = $entityManager->createQuery($dql)
    ->setMaxResults(1)
    ->getResult();

echo $logs;