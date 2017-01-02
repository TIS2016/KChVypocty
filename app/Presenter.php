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

    public static function getPaginationData($offset, $limit, $post) {
        $ret = "SELECT calc FROM 'App\Db\Calculation' calc WHERE ";

        if (isset($post)) {
            foreach ($post as $key => $value) {
                if ($key == 'job_type' && $value != '') {
                    $ret .= "calc." . "jobType" . " = " . "'" . $value . "'" . " and ";
                } elseif ($key == 'method' && $value != '') {
                    $ret .= "calc." . $key . " = " . "'" . $value . "'" . " and ";
                } elseif ($key == 'basisSet' && $value != '') {
                    $ret .= "calc." . $key . " = " . "'" . $value . "'" . " and ";
                } elseif ($key == 'stechiometry' && $value != '') {
                    $ret .= "calc." . $key . " = " . "'" . $value . "'" . " and ";
                }
            }
        }

        $ret = rtrim($ret, 'WHERE ');
        $ret = rtrim($ret, 'and ');

        $queryP = DoctrineSetup::getEntityManager()->createQuery($ret)
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        $paginator = new Paginator($queryP, $fetchJoinCollection = true);

        return $paginator;
    }


    public static function getDistinctValues($rowName){
        $dql = "SELECT DISTINCT calc.$rowName FROM App\Db\Calculation calc";
        $rows = DoctrineSetup::getEntityManager()->createQuery($dql)->getResult();

        $values = [];
        foreach ($rows as $row){
            $values[] = $row[$rowName];
        }

        return $values;
    }

    public static function getMethods($data){
        $methods = [];
        foreach ($data as $calculation){
            $methods[] = $calculation->getMethod();
        }
        return array_unique($methods);
    }

    public static function getBasisSets($data){
        $basisSets = [];
        foreach ($data as $calculation){
            $basisSets[] = $calculation->getBasisSet();
        }
        return array_unique($basisSets);
    }

    public static function getStechiometries($data){
        $stechiometries = [];
        foreach ($data as $calculation){
            $stechiometries[] = $calculation->getstechiometry();
        }
        return array_unique($stechiometries);
    }

    public static function getJobTypes($data){
        $jobTypes = [];
        foreach ($data as $calculation){
            $jobTypes[] = $calculation->getJobType();
        }
        return array_unique($jobTypes);
    }
}

