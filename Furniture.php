<?php

// furniture.php
class Furniture extends Product {
    private $dimensions;

    public function __construct($id,$sku, $name, $price, $dimensions) {
        parent::__construct($id,$sku, $name, $price);
        $this->dimensions = $dimensions;
    }

    public function getDimensions() {
        return $this->dimensions;
    }

    public function setDimensions($dimensions) {
        $this->dimensions = $dimensions;
    }

    public function getAttributes() {
        return "Dimensions: " . $this->dimensions;
    }

    public function toArray() {
        return array(
            'dimensions' => $this->getDimensions(),
            'id' => $this->getId(),
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
        );
    }
}

?>