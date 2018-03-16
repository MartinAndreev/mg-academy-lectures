<?php

abstract class iEngine {

    abstract public function getFuel();

    abstract public function getCyliders();

    abstract public function getLiters();

    abstract public function getHoursepower();

    public function getInfo() {
        return 'Cylinders:' . $this->getCyliders() . ' Fuel: ' . $this->getFuel() . ' Hoursepower: ' . $this->getHoursepower() . ' Liters: ' . $this->getLiters();
    }

}

interface iType {

    public function getType();

    public function getDoors();
}

interface iCar {

    public function setEngine(iEngine $engine);

    public function setType(iType $type);

    public function getType();

    public function getEngine();

    public function displayInfo();

    public function getModel();
}

class OM642 extends iEngine {

    public function getCyliders() {
        return 6;
    }

    public function getFuel() {
        return 'diesel';
    }

    public function getHoursepower() {
        return 224;
    }

    public function getLiters() {
        return 3;
    }

}

class OM113 extends iEngine {

    public function getCyliders() {
        return 8;
    }

    public function getFuel() {
        return 'petrol';
    }

    public function getHoursepower() {
        return 306;
    }

    public function getLiters() {
        return 5;
    }

}

class Combi implements iType {

    public function getDoors() {
        return 5;
    }

    public function getType() {
        return 'combi';
    }

}

class Sedan implements iType {

    public function getDoors() {
        return 5;
    }

    public function getType() {
        return 'sedan';
    }

}

class Coupe implements iType {

    public function getDoors() {
        return 3;
    }

    public function getType() {
        return 'couple';
    }

}

class Mercedes_w203 implements iCar {

    /**
     * Stores the engine
     * @var iEngine 
     */
    protected $_engine = null;

    /**
     * Stores the engine
     * @var iType 
     */
    protected $_type = null;

    public function displayInfo() {
        echo "Model: ", $this->getModel(), '<br />';
        echo "Engine: ", $this->getEngine()->getInfo(), '<br />';
        echo "Type: ", $this->getType()->getType(), " Doors: " . $this->getType()->getDoors();
    }

    public function setEngine(\iEngine $engine) {
        $this->_engine = $engine;
    }

    public function setType(\iType $type) {
        $this->_type = $type;
    }

    /**
     * 
     * @return iEngine
     */
    public function getEngine() {
        return $this->_engine;
    }

    /**
     * 
     * @return iType
     */
    public function getType() {
        return $this->_type;
    }

    public function getModel() {
        return 'C klasse w203';
    }

}

$disel = new OM642();
$petrol = new OM113();
$combi = new Combi();
$sedan = new Sedan();

$car = new Mercedes_w203();
$car->setEngine($disel);
$car->setType($combi);

//$car->displayInfo();
//echo '<br /> ================================ <br />';

$car2 = new Mercedes_w203();
$car2->setEngine($petrol);
$car2->setType($sedan);

//$car2->displayInfo();

$cars = [$car, $car2];

foreach ($cars as $selectedCar) {

    if ($selectedCar instanceof iCar) {
        if ($selectedCar->getEngine()->getHoursepower() < 240 && $selectedCar->getEngine()->getFuel() == 'diesel' && $selectedCar->getType()->getDoors() >= 4) {
            echo 'I will buy it! <br />';
            echo $selectedCar->displayInfo();
        }
    }
}