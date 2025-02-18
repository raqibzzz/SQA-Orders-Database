<?php
require_once 'config/database.php';
header('Content-Type: application/json');

function formatTags($tags) {
    if (empty($tags)) return '';
    $tagArray = explode(',', $tags);
    $output = '';
    foreach ($tagArray as $tag) {
        $tag = trim($tag);
        if (!empty($tag)) {
            $output .= "<span class='tag'>" . htmlspecialchars($tag) . "</span>";
        }
    }
    return $output;
}

try {
    $db = Database::getInstance();
    
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'desc';
    $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
    
    if (!in_array($sort, ['asc', 'desc'])) {
        $sort = 'desc';
    }
    
    $sql = "SELECT * FROM item_orders";
    if (!empty($filter)) {
        $sql .= " WHERE REQ_PO_Number LIKE ?";
    }
    $sql .= " ORDER BY Order_Date " . strtoupper($sort) . ", id DESC";
    $sql .= " LIMIT 100";
    
    if (!empty($filter)) {
        $stmt = $db->secureQuery($sql, "s", ["%$filter%"]);
    } else {
        $stmt = $db->prepare($sql);
        $stmt->execute();
    }
    
    $result = $stmt->get_result();
    $output = "";
    
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr data-id='{$row['id']}'>
            <td>{$row['Type']}</td>
            <td>{$row['Model_Name']}</td>
            <td>{$row['Quantity']}</td>
            <td class='description-cell'>
                <button onclick=\"openDescriptionModal('{$row['id']}', '" . htmlspecialchars($row['Description'], ENT_QUOTES) . "')\" class='description-button'>
                    <i class='fas fa-file-alt'></i>
                </button>
            </td>
            <td>{$row['REQ_PO_Number']}</td>
            <td>{$row['Order_Date']}</td>
            <td>{$row['Order_Status']}</td>
            <td>" . formatTags($row['Tags']) . "</td>
            <td class='notes-cell'>
                <button onclick=\"openNotesModal('{$row['id']}', '" . htmlspecialchars($row['Notes'], ENT_QUOTES) . "')\" class='notes-button'>
                    <i class='fas fa-sticky-note'></i>
                </button>
            </td>
            <td>
                <div class='action-buttons'>
                    <button onclick='editRow(this.closest(\"tr\"))' class='action-button edit'>
                        <i class='fas fa-edit'></i> Edit
                    </button>
                    <button onclick='deleteRow(this.closest(\"tr\"))' class='action-button delete'>
                        <i class='fas fa-trash'></i> Delete
                    </button>
                </div>
            </td>
        </tr>";
    }
    
    echo json_encode([
        'rows' => $output,
        'sortDirection' => $sort
    ]);
    
    $stmt->close();
} catch (Exception $e) {
    error_log("Error in get_sorted_data.php: " . $e->getMessage());
    echo json_encode(["error" => "Error fetching sorted data"]);
}
?>