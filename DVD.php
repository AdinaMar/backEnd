<?php 
// dvd.php
class DVD extends Product {
    private $size;

    public function __construct($id, $sku, $name, $price, $size) {
        parent::__construct($id, $sku, $name, $price);
        $this->size = $size;
    }
    public function getSize() {
        return $this->size;
    }

    public function setSize($size) {
        $this->size = $size;
    }

    public function getAttributes() {
        return "Size: " . $this->size;
    }

    public function toArray() {
        return array(
            'size' => $this->getSize(),
            'id' => $this->getId(),
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
        );
    }
}





?>