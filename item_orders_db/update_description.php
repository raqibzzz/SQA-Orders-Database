<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Get the raw input and log it
    $raw_input = file_get_contents('php://input');
    error_log("Received raw input: " . $raw_input);

    // Try to decode the JSON
    $data = json_decode($raw_input, true);
    
    // Check for JSON decode errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON: " . json_last_error_msg());
    }

    // Validate required fields
    if (!isset($data['id']) || !isset($data['description'])) {
        throw new Exception("Missing required fields (id or description)");
    }

    require_once 'config/database.php';
    $db = Database::getInstance()->getConnection();
    
    $id = intval($data['id']);
    $description = htmlspecialchars($data['description'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
    
    // Try updating in item_orders
    $sql = "UPDATE item_orders SET Description = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $db->error);
    }
    
    $stmt->bind_param("si", $description, $id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(["message" => "Description updated successfully"]);
    } else {
        // If no rows affected, try closed_item_orders
        $stmt->close();
        
        $sql = "UPDATE closed_item_orders SET Description = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $db->error);
        }
        
        $stmt->bind_param("si", $description, $id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            echo json_encode(["message" => "Description updated successfully"]);
        } else {
            echo json_encode(["error" => "No record found with ID: $id"]);
        }
    }
    
    $stmt->close();

} catch (Exception $e) {
    error_log("Error in update_description.php: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    http_response_code(400);
    echo json_encode([
        "error" => $e->getMessage(),
        "details" => "Check server logs for more information"
    ]);
}
?>