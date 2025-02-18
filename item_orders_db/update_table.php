<?php
// Prevent any output before headers
ob_start();

// Ensure no errors are output to the response
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once 'config/database.php';

// Set JSON header immediately
header('Content-Type: application/json');

try {
    $db = Database::getInstance();
    
    // Get the posted data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data received');
    }
    
    // Validate required fields
    $requiredFields = ['id', 'type', 'model_name', 'quantity', 'description', 
                      'req_po_number', 'order_date', 'order_status', 'tags', 'notes'];
    
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    // Check if request is from closed records page
    $fromClosed = isset($data['from_closed']) && $data['from_closed'];

    if ($fromClosed && $data['order_status'] !== 'Closed') {
        // Moving from closed_item_orders back to item_orders
        $insertSql = "INSERT INTO inventory.item_orders 
            (id, Type, Model_Name, Quantity, Description, REQ_PO_Number, 
             Order_Date, Order_Status, Tags, Notes) 
            SELECT id, Type, Model_Name, Quantity, Description, REQ_PO_Number,
                   Order_Date, ?, Tags, Notes 
            FROM inventory.closed_item_orders 
            WHERE id = ?";
        
        $insertStmt = $db->secureQuery($insertSql, "si", [$data['order_status'], intval($data['id'])]);
        
        if ($insertStmt->affected_rows > 0) {
            // Then delete from closed_item_orders
            $deleteSql = "DELETE FROM inventory.closed_item_orders WHERE id = ?";
            $deleteStmt = $db->secureQuery($deleteSql, "i", [intval($data['id'])]);
            
            if ($deleteStmt->affected_rows > 0) {
                echo json_encode(["message" => "Record restored to active items"]);
            } else {
                throw new Exception("Failed to delete from closed records");
            }
        } else {
            throw new Exception("Failed to restore record to active items");
        }
    } else if (!$fromClosed && $data['order_status'] === 'Closed') {
        // Moving from item_orders to closed_item_orders
        $insertSql = "INSERT INTO inventory.closed_item_orders 
            (id, Type, Model_Name, Quantity, Description, REQ_PO_Number, 
             Order_Date, Order_Status, Tags, Notes) 
            SELECT id, Type, Model_Name, Quantity, Description, REQ_PO_Number,
                   Order_Date, ?, Tags, Notes 
            FROM inventory.item_orders 
            WHERE id = ?";
        
        $insertStmt = $db->secureQuery($insertSql, "si", [$data['order_status'], intval($data['id'])]);
        
        if ($insertStmt->affected_rows > 0) {
            // Then delete from item_orders
            $deleteSql = "DELETE FROM inventory.item_orders WHERE id = ?";
            $deleteStmt = $db->secureQuery($deleteSql, "i", [intval($data['id'])]);
            
            if ($deleteStmt->affected_rows > 0) {
                echo json_encode(["message" => "Record moved to closed items"]);
            } else {
                throw new Exception("Failed to delete from active records");
            }
        } else {
            throw new Exception("Failed to move record to closed items");
        }
    } else {
        // Regular update to the appropriate table
        $table = $fromClosed ? 'closed_item_orders' : 'item_orders';
        $sql = "UPDATE inventory.$table SET 
                Type = ?, 
                Model_Name = ?, 
                Quantity = ?, 
                Description = ?, 
                REQ_PO_Number = ?, 
                Order_Date = ?, 
                Order_Status = ?,
                Tags = ?,
                Notes = ? 
                WHERE id = ?";
                
        $params = [
            $data['type'],
            $data['model_name'],
            intval($data['quantity']),
            $data['description'],
            $data['req_po_number'],
            $data['order_date'],
            $data['order_status'],
            $data['tags'],
            $data['notes'],
            intval($data['id'])
        ];
        
        $stmt = $db->secureQuery($sql, "ssissssssi", $params);
        
        if ($stmt->affected_rows > 0) {
            echo json_encode(["message" => "Record updated successfully"]);
        } else {
            throw new Exception("No changes made or record not found");
        }
    }
    
} catch (Exception $e) {
    error_log("Error in update_table.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    // Clear any buffered output
    ob_end_clean();
}
?>