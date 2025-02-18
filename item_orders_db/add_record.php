<?php
require_once 'config/database.php';

try {
    $db = Database::getInstance();
    
    // Get form data and sanitize
    $type = htmlspecialchars($_POST['type']);
    $model_name = htmlspecialchars($_POST['model_name']);
    $quantity = intval($_POST['quantity']);
    $description = htmlspecialchars($_POST['description']);
    $req_po_number = htmlspecialchars($_POST['req_po_number']);
    $order_date = htmlspecialchars($_POST['order_date']);
    $order_status = htmlspecialchars($_POST['order_status']);
    $tags = htmlspecialchars($_POST['tags']);
    $notes = htmlspecialchars($_POST['notes']);

    // Use prepared statement
    $sql = "INSERT INTO item_orders (Type, Model_Name, Quantity, Description, REQ_PO_Number, Order_Date, Order_Status, Tags, Notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $types = "ssissssss"; // string, string, integer, string, string, string, string, string, string
    $params = [$type, $model_name, $quantity, $description, $req_po_number, $order_date, $order_status, $tags, $notes];
    
    $stmt = $db->secureQuery($sql, $types, $params);
    
    if ($stmt) {
        header("Location: frontend.php");
    } else {
        throw new Exception("Error adding record");
    }
} catch (Exception $e) {
    error_log("Error in add_record.php: " . $e->getMessage());
    echo "Error adding record. Please try again.";
}
?>