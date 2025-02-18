<?php
require_once 'config/database.php';

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

function escapeJsString($str) {
    return str_replace(
        array("\r", "\n", '"', "'", '<', '>'),
        array('\r', '\n', '&quot;', '&#39;', '&lt;', '&gt;'),
        $str
    );
}

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $sql = "SELECT * FROM item_orders ORDER BY id DESC LIMIT 100";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Prepare escaped values
            $id = htmlspecialchars($row['id']);
            $type = htmlspecialchars($row['Type']);
            $modelName = htmlspecialchars($row['Model_Name']);
            $quantity = htmlspecialchars($row['Quantity']);
            $reqPoNumber = htmlspecialchars($row['REQ_PO_Number']);
            $orderDate = htmlspecialchars($row['Order_Date']);
            $orderStatus = htmlspecialchars($row['Order_Status']);
            
            // Special handling for description and notes
            $description = escapeJsString($row['Description']);
            $notes = escapeJsString($row['Notes']);
            
            echo "<tr data-id='{$id}'>
                    <td>{$type}</td>
                    <td>{$modelName}</td>
                    <td>{$quantity}</td>
                    <td class='description-cell'>
                        <button type='button' 
                                class='description-button'
                                onclick='openDescriptionModal({$id}, `{$description}`);'>
                            <i class='fas fa-file-alt'></i>
                        </button>
                    </td>
                    <td>{$reqPoNumber}</td>
                    <td>{$orderDate}</td>
                    <td>{$orderStatus}</td>
                    <td>" . (isset($row['Tags']) ? formatTags($row['Tags']) : '') . "</td>
                    <td class='notes-cell'>
                        <button type='button' 
                                class='notes-button'
                                onclick='openNotesModal({$id}, `{$notes}`);'>
                            <i class='fas fa-sticky-note'></i>
                        </button>
                    </td>
                    <td>
                        <div class='action-buttons'>
                            <button type='button' 
                                    onclick='editRow(this.closest(`tr`))' 
                                    class='action-button edit'>
                                <i class='fas fa-edit'></i> Edit
                            </button>
                            <button type='button' 
                                    onclick='deleteRow(this.closest(`tr`))' 
                                    class='action-button delete'>
                                <i class='fas fa-trash'></i> Delete
                            </button>
                        </div>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No data found</td></tr>";
    }
} catch (Exception $e) {
    error_log("Error in connection.php: " . $e->getMessage());
    echo "<tr><td colspan='10'>Error loading data. Please try again.</td></tr>";
}
?>