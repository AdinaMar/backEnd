<?php   
abstract class Product {
   protected $id;
   protected $sku;
   protected $name;
   protected $price;

   public function __construct($id, $sku, $name, $price){
    $this-> id = $id;
    $this-> sku = $sku;
    $this-> name = $name;
    $this-> price = $price;
   }

   public function getId() {
    return $this->id;
}

public function getSku() {
    return $this->sku;
}

public function getName() {
    return $this->name;
}

public function getPrice() {
    return $this->price;
}

public function setId($id) {
    $this->id = $id;
}

public function setSku($sku) {
    $this->sku = $sku;
}

public function setName($name) {
    $this->name = $name;
}

public function setPrice($price) {
    $this->price = $price;
}

abstract public function getAttributes();
}





?>