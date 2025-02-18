<?php
require_once __DIR__ . '/database.php';

class TableDataAccess {
    private $db;
    private $cacheEnabled = true;
    private $cacheExpiry = 300; // 5 minutes
    private $recordsPerPage = 50;
    
    public function __construct() {
        $this->db = Database::getInstance();    
}

public function getTableData($page = 1, $filters = [], $sort = null) {
    $cacheKey = $this->generateCacheKey($page, $filters, $sort);
    
    if ($this->cacheEnabled) {
        $cachedResult = $this->getFromCache($cacheKey);
        if ($cachedResult !== false) {
            return $cachedResult;
        }
    }
    
    $offset = ($page - 1) * $this->recordsPerPage;
    
    // Build base query with indexes
    $query = "SELECT * FROM item_orders USE INDEX (order_date_idx, req_po_number_idx)";
    
    // Add filters
    $whereClause = [];
    $params = [];
    $types = '';
    
    if (!empty($filters)) {
        foreach ($filters as $key => $value) {
            switch ($key) {
                case 'req_number':
                    $whereClause[] = "REQ_PO_Number LIKE ?";
                    $params[] = "%$value%";
                    $types .= 's';
                    break;
                case 'status':
                    $whereClause[] = "Order_Status = ?";
                    $params[] = $value;
                    $types .= 's';
                    break;
                case 'date_range':
                    $whereClause[] = "Order_Date BETWEEN ? AND ?";
                    $params[] = $value['start'];
                    $params[] = $value['end'];
                    $types .= 'ss';
                    break;
            }
        }
    }
    
    if (!empty($whereClause)) {
        $query .= " WHERE " . implode(" AND ", $whereClause);
    }
    
    // Add sorting
    if ($sort) {
        $query .= " ORDER BY {$sort['column']} {$sort['direction']}";
    } else {
        $query .= " ORDER BY Order_Date DESC";
    }
    
    // Add pagination
    $query .= " LIMIT ? OFFSET ?";
    $params[] = $this->recordsPerPage;
    $params[] = $offset;
    $types .= 'ii';
    
    // Execute query
    try {
        $result = $this->db->query($query, $params, $types);
        $data = $result->fetch_all(MYSQLI_ASSOC);
        
        // Get total count for pagination
        $countQuery = "SELECT COUNT(*) as total FROM item_orders";
        if (!empty($whereClause)) {
            $countQuery .= " WHERE " . implode(" AND ", $whereClause);
        }
        $totalResult = $this->db->query($countQuery, array_slice($params, 0, -2), rtrim($types, 'ii'));
        $total = $totalResult->fetch_assoc()['total'];
        
        $response = [
            'data' => $data,
            'total' => $total,
            'pages' => ceil($total / $this->recordsPerPage)
        ];
        
        if ($this->cacheEnabled) {
            $this->setCache($cacheKey, $response);
        }
        
        return $response;
    } catch (Exception $e) {
        error_log("Error fetching table data: " . $e->getMessage());
        throw new Exception("Error retrieving data");
    }
}

private function generateCacheKey($page, $filters, $sort) {
    return md5(json_encode([
        'page' => $page,
        'filters' => $filters,
        'sort' => $sort
    ]));
}

private function getFromCache($key) {
    $cacheFile = sys_get_temp_dir() . '/table_cache_' . $key;
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $this->cacheExpiry) {
        return json_decode(file_get_contents($cacheFile), true);
    }
    return false;
}

private function setCache($key, $data) {
    $cacheFile = sys_get_temp_dir() . '/table_cache_' . $key;
    file_put_contents($cacheFile, json_encode($data));
}
}