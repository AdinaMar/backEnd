<?php
require_once './database.php';

// Create a new instance of the Database class
$database = new Database();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type'); 
// Get the data from the HTTP POST request


// Extract the data from the request
$sku = $_POST['sku'];
$name = $_POST['name'];
$price = $_POST['price'];
$attributeType = $_POST['attributeType'];
$attributeValue = $_POST['attributeValue'];

// Call the addItem function to save the item to the database
$newProductID = $database->addItem($sku, $name, $price, $attributeType, $attributeValue);

// Return the product ID of the newly added item as a JSON response
$response = array('productID' => $newProductID);
header('Content-Type: application/json');
echo json_encode($response);
?>
