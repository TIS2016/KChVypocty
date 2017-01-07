<?php

namespace App;

use App\Db\Calculation;
use App\Db\Coordinate;
use App\Db\History;

/**
 * Class CalculationResult
 * @package App
 *
 * Represents calculations result and inserts them into database
 *
 */
class CalculationResult {
    private $tokens;
    private $body;
    private $coordinates;
    private $tail;
    private $path;
    private $energy;
    private $thermoChemistry;

    public function __construct($tokens, $body, $coordinates, $tail, $path, $energy, $thermoChemistry) {
        $this->tokens = $tokens;
        $this->body = $body;
        $this->coordinates = $coordinates;
        $this->tail = $tail;
        $this->path = $path;
        $this->energy = $energy;
        $this->thermoChemistry = $thermoChemistry;
    }

    /**
     * @return method name
     *
     * Helper method
     *
     */
    public function getMethod() {
        return $this->tokens[2];
    }

    /**
     * Inserts data to the database with doctrine ORM
     */
    public function insert() {
        $calculationDatabase = new Calculation();
        $calculationDatabase->setServer($this->tokens[0]);
        $calculationDatabase->setJobType($this->tokens[1]);
        $calculationDatabase->setMethod($this->tokens[2]);
        $calculationDatabase->setBasisSet($this->tokens[3]);
        $calculationDatabase->setStechiometry($this->tokens[4]);
        $calculationDatabase->setUser($this->tokens[5]);
        $calculationDatabase->setDate($this->tokens[6]);
        $calculationDatabase->setInfoInput($this->body);
        $calculationDatabase->setInfoEnd($this->tail);
        $calculationDatabase->setPath($this->path);
        $calculationDatabase->setEnergy($this->energy);
        $calculationDatabase->setThermoChemistry($this->thermoChemistry);

        DoctrineSetup::getEntityManager()->persist($calculationDatabase);
        DoctrineSetup::getEntityManager()->flush();

        $coordinatesDatabase = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->coordinates as $coordinate) {
            $coordinateDatabase = new Coordinate();
            $coordinateDatabase->setAtom($coordinate[0]);
            $coordinateDatabase->setX(floatval($coordinate[1]));
            $coordinateDatabase->setY(floatval($coordinate[2]));
            $coordinateDatabase->setZ(floatval($coordinate[3]));
            $coordinateDatabase->setCalculation($calculationDatabase);
            $coordinatesDatabase->add($coordinateDatabase);
        }
        $calculationDatabase->setCoordinates($coordinatesDatabase);
        DoctrineSetup::getEntityManager()->persist($calculationDatabase);
        DoctrineSetup::getEntityManager()->flush();

        $history = new History();
        $history->setPath($this->path);

        DoctrineSetup::getEntityManager()->persist($history);
        DoctrineSetup::getEntityManager()->flush();
    }

}