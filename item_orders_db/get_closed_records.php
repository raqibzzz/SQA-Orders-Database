<?php
require_once 'config/database.php';

try {
    $db = Database::getInstance();
    
    $sql = "SELECT * FROM inventory.closed_item_orders ORDER BY Order_Date DESC";
    $stmt = $db->secureQuery($sql, "", []); // Using your existing secureQuery method
    
    $results = array();
    $result = $stmt->get_result();
    
    // Fetch the results using mysqli methods
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($results);
    
    $stmt->close();
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>