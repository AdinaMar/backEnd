<?php

require_once './database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');

    // Get the raw POST data
    $postData = $_POST['selectedProductIds'];
 
    $requestData = json_decode($postData, true);

    if (isset($postData)) {
        // Retrieve the selected product IDs from the request data
        $selectedProductIds = $postData;

        // Convert the comma-separated string of IDs into an array (if it's not already an array)
        if (!is_array($selectedProductIds)) {
            $selectedProductIds = explode(',', $selectedProductIds);
        }

        // Create a new instance of the Database class
        $db = new Database();

        // Call the massDeleteItems function to perform the deletion
        $result = $db->massDeleteItems($selectedProductIds);

        // Return a JSON response indicating the result of the deletion
        echo json_encode(array('success' => $result === true, 'error' => $result));
    } else {
        // Return an error response if 'selectedProductIds' is not found in the request data
        echo json_encode(array('success' => false, 'error' => 'Selected Product IDs not found in the request.'));
    }
}
