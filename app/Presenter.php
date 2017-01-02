<?php
namespace App;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Presenter {
    public static function getTableData(){
        $calculationsRepo = DoctrineSetup::getEntityManager()->getRepository('App\Db\Calculation');
        return $calculationsRepo->findAll();
    }

    public static function getCalculationData($id){
        $calculationRepo = DoctrineSetup::getEntityManager()->getRepository('App\Db\Calculation');
        return $calculationRepo->find($id);
    }

    public static function getReports()
    {
        $reports = DoctrineSetup::getEntityManager()->getRepository('App\Db\Logs');
        return $reports->findAll();
    }
    public static function getPagiData($offset, $limit) {
        $queryP = DoctrineSetup::getEntityManager()->createQuery("SELECT calc FROM 'App\Db\Calculation' calc")
                                            ->setFirstResult($offset)
                                            ->setMaxResults($limit);
        $paginator = new Paginator($queryP, $fetchJoinCollection = true);

        return $paginator;
    }


    public static function getDistinctValues($rowName){
        $dql = "SELECT DISTINCT calc.$rowName FROM App\Db\Calculation calc";
        $rows = DoctrineSetup::getEntityManager()->createQuery($dql)->getResult();
        return $rows;
    }
}

