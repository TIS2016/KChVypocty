<?php
require_once "vendor/autoload.php";
use App\Db\Calculation;
use App\DoctrineSetup;

$faker = Faker\Factory::create();
for ($i = 0; $i < 500; $i++) {
    $calculation = new Calculation();
    $calculation->setBasisSet($faker->text(5));
    $calculation->setDate($faker->dateTime);
    $calculation->setJobType($faker->text(5));
    $calculation->setMethod($faker->text(5));
    $calculation->setPath($faker->text(5));
    $calculation->setServer($faker->text(5));
    $calculation->setStechiometry($faker->text(5));
    $calculation->setUser($faker->name);
    DoctrineSetup::getEntityManager()->persist($calculation);
    DoctrineSetup::getEntityManager()->flush();
}


