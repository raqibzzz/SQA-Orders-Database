<?php
header('Content-Type: application/json');

require_once 'config/database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Get and validate input
    $json_input = file_get_contents('php://input');
    $data = json_decode($json_input, true);
    
    if (!isset($data['id']) || !isset($data['notes'])) {
        throw new Exception("Missing required fields");
    }
    
    $id = intval($data['id']);
    $notes = htmlspecialchars($data['notes'], ENT_QUOTES, 'UTF-8');
    
    // Prepare and execute update for active items
    $stmt = $conn->prepare("UPDATE item_orders SET Notes = ? WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("si", $notes, $id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        $stmt->close();
        echo json_encode([
            "message" => "Notes updated successfully",
            "notes" => $notes
        ]);
        exit;
    }
    
    $stmt->close();
    
    // If no rows affected, try closed items
    $stmt = $conn->prepare("UPDATE closed_item_orders SET Notes = ? WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed for closed items: " . $conn->error);
    }
    
    $stmt->bind_param("si", $notes, $id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        $stmt->close();
        echo json_encode([
            "message" => "Notes updated successfully",
            "notes" => $notes
        ]);
        exit;
    }
    
    $stmt->close();
    
    // If we get here, no records were updated
    echo json_encode(["error" => "No record found with ID: $id"]);
    
} catch (Exception $e) {
    error_log("Error in update_notes.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "error" => "Error updating notes",
        "details" => $e->getMessage()
    ]);
}
?>