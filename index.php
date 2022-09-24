<?php
abstract class Animal
{
    protected int $productCount;
    protected int $ID;
    protected string $animalType;
    protected string $productType;

    public function __construct()
    {
        $this->addProduct();
    }
    public function getProduct()
    {
        return $this->productCount;
    }
    public function getAnimalType()
    {
        return $this->animalType;
    }
    public function getProductType()
    {
        return $this->productType;
    }
    public function getID()
    {
        return $this->ID;
    }
    public function setID($regNumber)
    {
        $this->ID = $regNumber;
    }
    public function emptyProduct()
    {
        $this->productCount = 0;
    }
    abstract public function addProduct();
}

class Cow extends Animal
{
    protected string $animalType = "Корова";
    protected string $productType = "молока литров";

    public function addProduct()
    {
        $this->productCount = rand(8, 12);
    }
}

class Chicken extends Animal
{
    protected string $animalType = "Курица";
    protected string $productType = "яиц шт.";

    public function addProduct()
    {
        $this->productCount = rand(0, 1);
    }
}

class Farm
{
    private array $animals;
    private array $products;

    public function __construct()
    {
        for ($i = 0; $i < 10; $i++)
        {
            $this->addAnimal(Cow::class);
        }
        for ($i = 0; $i < 20; $i++)
        {
            $this->addAnimal(Chicken::class);
        }
    }
    public function addAnimal($className)
    {
        if (!isset($this->animals[$className]))
        {
            $this->animals[$className] = [];
        }
        $newAnimal = $this->animals[$className][] = new $className;
        $animalID = $this->giveID();
        $newAnimal->setID($animalID);
        if (!isset($this->products[$newAnimal->getProductType()]))
        {
            $this->products[$newAnimal->getProductType()] = 0;
        }
    }
    private function giveID()
    {
        $characters = '0123456789';
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return intval($randomString);
    }
    private function collectProduct($animalType)
    {
        foreach ($this->animals[$animalType] as $animal)
        {
            $this->products[$animal->getProductType()] += $animal->getProduct();
            $animal->emptyProduct();
        }
    }
    public function collectWeek()
    {
        for ($i = 0; $i < 7; $i++)
        {
            foreach (array_keys($this->animals) as $key)
            {
                $this->collectProduct($key);
            }
            $this->newDay();
        }
    }
    private function newDay()
    {
        foreach ($this->animals as $animals)
        {
            foreach ($animals as $animal)
            {
                $animal->addProduct();
            }
        }
    }
    public function clearProducts()
    {
        $this->products = array();
    }
    public function showAnimals()
    {
        echo "Всего на ферме:\n";
        foreach ($this->animals as $animals)
        {
            echo $animals[0]->getAnimalType().": ".count($animals)." шт.\n";
        }
    }
    public function showProducts()
    {
        echo "Собрано за неделю:\n";
        foreach ($this->products as $type => $product)
        {
            echo "- ".$type." ".$product."\n";
        }
    }
}

$farm = new Farm();
$farm->showAnimals();
$farm->collectWeek();
$farm->showProducts();
$farm->clearProducts();
for ($i = 0; $i < 5; $i++)
{
    $farm->addAnimal(Chicken::class);
}
$farm->addAnimal(Cow::class);
$farm->showAnimals();
$farm->collectWeek();
$farm->showProducts();
$farm->clearProducts();
?>