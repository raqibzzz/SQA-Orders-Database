<?php
require_once 'config/database.php';

try {
    $db = Database::getInstance();
    
    // Get the posted data
    if (!isset($_POST['id'])) {
        throw new Exception("Missing ID parameter");
    }
    
    $id = intval($_POST['id']);
    
    // Use prepared statement for deletion
    $sql = "DELETE FROM item_orders WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Record deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "error" => "No record found with that ID"]);
    }
    
    $stmt->close();
} catch (Exception $e) {
    error_log("Error in delete_record.php: " . $e->getMessage());
    echo json_encode(["success" => false, "error" => "Error deleting record"]);
}
?>