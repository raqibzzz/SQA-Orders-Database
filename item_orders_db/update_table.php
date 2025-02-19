<?php
// Enable error logging
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
error_reporting(E_ALL);

// Start output buffering
ob_start();

require_once 'config/database.php';

try {
    // Get and log raw input
    $input = file_get_contents('php://input');
    error_log("Received raw input: " . $input);
    
    // Decode JSON
    $data = json_decode($input, true);
    
    // Log JSON decode errors if any
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSON decode error: ' . json_last_error_msg());
    }
    
    error_log("Decoded data: " . print_r($data, true));

    // Validate required fields
    $requiredFields = ['id', 'type', 'model_name', 'quantity', 'description', 
                      'req_po_number', 'order_date', 'order_status', 'tags', 'notes'];
    
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    $db = Database::getInstance();
    $conn = $db->getConnection();

    // Regular update without moving between tables
    $table = isset($data['from_closed']) && $data['from_closed'] ? 'closed_item_orders' : 'item_orders';
    
    // Log the query we're about to execute
    error_log("Executing update on table: " . $table);

    $stmt = $conn->prepare("UPDATE $table SET 
            Type = ?, 
            Model_Name = ?, 
            Quantity = ?, 
            Description = ?, 
            REQ_PO_Number = ?, 
            Order_Date = ?, 
            Order_Status = ?,
            Tags = ?,
            Notes = ? 
            WHERE id = ?");

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Store values in variables for binding
    $type = $data['type'];
    $modelName = $data['model_name'];
    $quantity = intval($data['quantity']);
    $description = $data['description'];
    $reqPoNumber = $data['req_po_number'];
    $orderDate = $data['order_date'];
    $orderStatus = $data['order_status'];
    $tags = $data['tags'];
    $notes = $data['notes'];
    $id = intval($data['id']);

    // Log the values we're binding
    error_log("Binding parameters: " . print_r([
        'type' => $type,
        'model_name' => $modelName,
        'quantity' => $quantity,
        'req_po_number' => $reqPoNumber,
        'order_date' => $orderDate,
        'order_status' => $orderStatus,
        'id' => $id
    ], true));

    $bindResult = $stmt->bind_param("ssissssssi",
        $type,
        $modelName,
        $quantity,
        $description,
        $reqPoNumber,
        $orderDate,
        $orderStatus,
        $tags,
        $notes,
        $id
    );

    if (!$bindResult) {
        throw new Exception("Bind failed: " . $stmt->error);
    }

    $executeResult = $stmt->execute();
    if (!$executeResult) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    // Set the content type header
    header('Content-Type: application/json');

    if ($stmt->affected_rows > 0) {
        echo json_encode(["message" => "Record updated successfully"]);
    } else {
        // Log that no rows were affected
        error_log("No rows affected by update. SQL Error: " . $stmt->error);
        throw new Exception("No changes made or record not found");
    }

    $stmt->close();

} catch (Exception $e) {
    // Log the full error
    error_log("Error in update_table.php: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    // Set error response code
    http_response_code(500);
    
    // Ensure we're sending JSON response
    header('Content-Type: application/json');
    
    // Send error response
    echo json_encode(["error" => $e->getMessage()]);
}

// End output buffering
ob_end_flush();
?>