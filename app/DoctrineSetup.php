<?php
namespace App;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

/**
 * Class DoctrineSetup
 * @package App
 *
 * Provides entity manager for other classes in application
 *
 */
class DoctrineSetup {

    private static $entityManager = null;

    /**
     * @return EntityManager|null
     * Static function which retursn entity manager
     */
    public static function getEntityManager(){
        if (self::$entityManager === null) {
            self::$entityManager = self::createEntityManager();
        }
        return self::$entityManager;
    }

    /**
     * DoctrineSetup constructor.
     * Private constructor - this class is not instansiable
     */
    private function __construct(){}

    /**
     * @return EntityManager
     *
     * Creates new entity manager
     */
    private static function createEntityManager(){

        $isDevMode = true;
        $entityFilesLocation = array('app/database_entities/');

        $conn = array(
            'driver' => 'pdo_mysql',
            'user' => 'root',
            'password' => 'root',
            'dbname' => 'tis'
        );

        $config = Setup::createAnnotationMetadataConfiguration($entityFilesLocation, $isDevMode);
        $entityManager = EntityManager::create($conn, $config);

        return $entityManager;
    }
}



