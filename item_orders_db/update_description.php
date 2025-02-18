<?php
header('Content-Type: application/json');

require_once 'config/database.php';

try {
    $db = Database::getInstance();
    
    $json_input = file_get_contents('php://input');
    $data = json_decode($json_input, true);
    
    if (!isset($data['id']) || !isset($data['description'])) {
        throw new Exception("Missing required fields");
    }
    
    $id = intval($data['id']);
    $description = $data['description'];
    
    // Try updating in item_orders first
    $sql = "UPDATE item_orders SET Description = ? WHERE id = ?";
    $stmt = $db->secureQuery($sql, "si", [$description, $id]);
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(["message" => "Description updated successfully"]);
    } else {
        // If no rows affected, try closed_item_orders
        $sql = "UPDATE closed_item_orders SET Description = ? WHERE id = ?";
        $stmt = $db->secureQuery($sql, "si", [$description, $id]);
        
        if ($stmt->affected_rows > 0) {
            echo json_encode(["message" => "Description updated successfully"]);
        } else {
            echo json_encode(["error" => "No record found with that ID"]);
        }
    }
    
    $stmt->close();
} catch (Exception $e) {
    error_log("Error in update_description.php: " . $e->getMessage());
    echo json_encode(["error" => "Error updating description"]);
}
?>