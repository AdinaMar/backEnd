<?php

class Book extends Product {
    private $weight;

    public function __construct($id,$sku, $name, $price, $weight) {
        parent::__construct($id,$sku, $name, $price);
        $this->weight = $weight;
    }

    public function getWeight() {
        return $this->weight;
    }

    public function setWeight($weight) {
        $this->weight = $weight;
    }

    public function getAttributes() {
        return "Weight: " . $this->weight;
    }

    public function toArray() {
        return array(
            'weight' => $this->getWeight(),
            'id' => $this->getId(),
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
        );
    }
}
?>
