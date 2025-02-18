<?php
require_once 'config/database.php';

try {
    $db = Database::getInstance();
    
    // Get the posted data from AJAX
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id'])) {
        throw new Exception("Missing ID parameter");
    }
    
    $id = intval($data['id']);
    
    // Use prepared statement for deletion
    $sql = "DELETE FROM item_orders WHERE id = ?";
    $stmt = $db->secureQuery($sql, "i", [$id]);
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(["message" => "Record deleted successfully"]);
    } else {
        echo json_encode(["error" => "No record found with that ID"]);
    }
    
    $stmt->close();
} catch (Exception $e) {
    error_log("Error in delete_record.php: " . $e->getMessage());
    echo json_encode(["error" => "Error deleting record"]);
}
?>