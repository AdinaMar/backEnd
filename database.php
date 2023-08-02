<?php

require_once './Product.php';
require_once './Book.php';
require_once './DVD.php';
require_once './Furniture.php';

class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "data";
    private $connection;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $this->host = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->dbname = "data";
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function query($sql)
    {
        return $this->connection->query($sql);
    }

    public function fetchAllData($tableName)
    {
        $sql = "SELECT p.id, p.name, p.sku, p.price, a.attribute_type, a.attribute_value FROM products p LEFT JOIN attributes a ON p.id=a.product_id ORDER BY p.id";
        $result = $this->query($sql);
        $data = [];
        if ($result !== false && $result->rowCount() > 0) {
            $productTypes = [
                'Size' => 'DVD',
                'Weight' => 'Book',
                'Dimension' => 'Furniture',
            ];

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $productID = $row['id'];
                $productSku = $row['sku'];
                $productName = $row['name'];
                $productPrice = $row['price'];
                $attributeType = $row['attribute_type'];
                $attributeValue = $row['attribute_value'];

                if (array_key_exists($attributeType, $productTypes)) {
                    $className = $productTypes[$attributeType];
                    $product = new $className($productID, $productSku, $productName, $productPrice, $attributeValue);

                    $data[] = $product;
                } else {
                    // Handle unknown product types if necessary
                    continue;
                }
            }

            $productData = [];
            foreach ($data as $product) {
                $productData[] = $product->toArray();
            }

            // Perform JSON encoding on the $productData array
            $jsonData = json_encode($productData, JSON_PRETTY_PRINT);
            header('Content-Type: application/json');
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type');
            echo $jsonData;
        } else {
            echo "No data found in {$tableName}.";
        }
    }

    public function addItem($sku, $name, $price, $attributeType, $attributeValue)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type');

        try {
            if (empty($sku) || empty($name) || empty($price) || empty($attributeType) || empty($attributeValue)) {
                throw new Exception("Please submit required data.");
            }

            $sql = "SELECT COUNT(*) FROM products WHERE sku = :sku";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':sku', $sku);
            $stmt->execute();

            if ($stmt->fetchColumn() > 0) {
                throw new Exception("SKU already exists. Please provide a unique SKU.");
            }
            // Prepare the SQL statement with placeholders to avoid SQL injection
            $sql = "INSERT INTO products (sku, name, price) VALUES (:sku, :name, :price)";
            $stmt = $this->connection->prepare($sql);

            // Bind the parameters to the placeholders
            $stmt->bindParam(':sku', $sku);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);

            // Execute the SQL statement
            $stmt->execute();

            // Get the newly inserted product ID
            $productID = $this->connection->lastInsertId();

            // Insert the attribute into the attributes table
            $sql = "INSERT INTO attributes (product_id, attribute_type, attribute_value) VALUES (:productID, :attributeType, :attributeValue)";
            $stmt = $this->connection->prepare($sql);

            // Bind the parameters to the placeholders
            $stmt->bindParam(':productID', $productID);
            $stmt->bindParam(':attributeType', $attributeType);
            $stmt->bindParam(':attributeValue', $attributeValue);

            // Execute the SQL statement
            $stmt->execute();

            // Return the product ID of the newly added item
            return $productID;
        } catch (PDOException $e) {
            die("Error adding item: " . $e->getMessage());
        }
    }

    public function massDeleteItems($selectedProductIds)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type');
        try {
            // Prepare the SQL statement with placeholders for the selected product IDs
            $placeholders = implode(',', array_fill(0, count($selectedProductIds), '?'));
            $sql = "DELETE FROM products WHERE id IN ($placeholders)";
            $stmt = $this->connection->prepare($sql);

            // Bind the selected product IDs to the placeholders
            $stmt->execute($selectedProductIds);
            error_log('SQL Query: ' . $sql);
            // Return success if the deletion was successful
            return true;
        } catch (PDOException $e) {
            // Log or output the error message for debugging purposes
            error_log('Error during deletion: ' . $e->getMessage());

            // Return the error message if there was an error during deletion
            return 'Error during deletion: ' . $e->getMessage();
        }
    }
}
?>