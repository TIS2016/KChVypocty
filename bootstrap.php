<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

$isDevMode = true;
$entityFilesLocation = array('app/');

// database configuration parameters
$conn = array(
    'driver' => 'pdo_mysql',
    'user' => 'root',
    'password' => 'root',
    'dbname' => 'tis'
);

$config = Setup::createAnnotationMetadataConfiguration($entityFilesLocation, $isDevMode);
$entityManager = EntityManager::create($conn, $config);