<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

$isDevMode = true;
$entityFilesLocation = array('app/');

// TODO treba zmenit udaje do vasej vlastnej databazy
$conn = array(
    'driver' => 'pdo_mysql',
    'user' => 'root',
    'password' => 'root',
    'dbname' => 'tis'
);

$config = Setup::createAnnotationMetadataConfiguration($entityFilesLocation, $isDevMode);
$entityManager = EntityManager::create($conn, $config);