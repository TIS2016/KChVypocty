<?php
namespace App;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;


/* Simple singleton class */
class DoctrineSetup {

    private static $entityManager = null;

    public static function getEntityManager(){
        if (self::$entityManager === null) {
            self::$entityManager = self::createEntityManager();
        }
        return self::$entityManager;
    }

    private function __construct(){}

    private static function createEntityManager(){

        $isDevMode = true;
        $entityFilesLocation = array('app/database_entities/');

        // TODO treba zmenit udaje do vasej vlastnej databazy
        $conn = array(
            'driver' => 'pdo_mysql',
            'user' => 'root',
            'password' => 'fsociety',
            'dbname' => 'tis'
        );

        $config = Setup::createAnnotationMetadataConfiguration($entityFilesLocation, $isDevMode);
        $entityManager = EntityManager::create($conn, $config);

        return $entityManager;
    }


}



