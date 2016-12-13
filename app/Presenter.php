<?php
namespace App;


class Presenter {
    public static function getTableData(){
        $calculationsRepo = DoctrineSetup::getEntityManager()->getRepository('App\Db\Calculation');

        return $calculationsRepo->findAll();
    }

    public static function getCalculationData($id){
        $calculationRepo = DoctrineSetup::getEntityManager()->getRepository('App\Db\Calculation');
        return $calculationRepo->find($id);
    }



    public static function run(){




    }

}

