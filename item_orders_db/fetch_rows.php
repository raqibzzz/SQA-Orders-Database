<?php
require_once 'config/database.php';

try {
    $db = Database::getInstance();
    
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $pageSize = isset($_GET['pageSize']) ? max(1, intval($_GET['pageSize'])) : 20;
    $offset = ($page - 1) * $pageSize;
    
    // Get total count for pagination
    $countResult = $db->query("SELECT COUNT(*) as total FROM item_orders");
    $totalRows = $countResult->fetch_assoc()['total'];
    
    // Fetch paginated data with ORDER BY
    $sql = "SELECT id, Type, Model_Name, Quantity, Description, REQ_PO_Number, 
            Order_Date, Order_Status, Notes 
            FROM item_orders 
            ORDER BY id DESC
            LIMIT ?, ?";
            
    $stmt = $db->secureQuery($sql, "ii", [$offset, $pageSize]);
    $result = $stmt->get_result();
    
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    
    echo json_encode([
        'rows' => $rows,
        'total' => $totalRows,
        'page' => $page,
        'pageSize' => $pageSize,
        'totalPages' => ceil($totalRows / $pageSize)
    ]);
    
    $stmt->close();
} catch (Exception $e) {
    error_log("Error in fetch_rows.php: " . $e->getMessage());
    echo json_encode(["error" => "Error fetching records"]);
}
?>