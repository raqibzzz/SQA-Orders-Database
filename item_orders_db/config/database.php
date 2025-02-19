<?php
class Database {
    private static $instance = null;
    private $connection;
    
    private $config = [
        'host' => '192.168.180.215',
        'username' => 'xautosqa',
        'password' => 'x56789',
        'dbname' => 'item_orders_db',
        'port' => 3306
    ];

    private function __construct() {
        try {
            $this->connection = new mysqli(
                $this->config['host'],
                $this->config['username'],
                $this->config['password'],
                $this->config['dbname'],
                $this->config['port']
            );

            if ($this->connection->connect_error) {
                throw new Exception("Connection failed: " . $this->connection->connect_error);
            }

            // Performance optimizations
            $this->connection->set_charset("utf8mb4");
            
            // Optimize session settings
            $this->connection->query("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");
            $this->connection->query("SET SESSION wait_timeout=120");
            $this->connection->query("SET SESSION interactive_timeout=120");
            $this->connection->query("SET SESSION max_allowed_packet=16777216");
            
            // Set up indexes if they don't exist
            $this->setupIndexes();
            
        } catch (Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw new Exception("Database connection error occurred");
        }
    }

    private function setupIndexes() {
        $indexes = [
            "CREATE INDEX IF NOT EXISTS idx_order_date ON item_orders(Order_Date)",
            "CREATE INDEX IF NOT EXISTS idx_req_po ON item_orders(REQ_PO_Number)",
            "CREATE INDEX IF NOT EXISTS idx_status ON item_orders(Order_Status)"
        ];

        foreach ($indexes as $sql) {
            try {
                $this->connection->query($sql);
            } catch (Exception $e) {
                error_log("Index creation warning: " . $e->getMessage());
            }
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        // Check if connection is still alive
        if (!$this->connection->ping()) {
            $this->connection = null;
            self::$instance = null;
            return self::getInstance()->connection;
        }
        return $this->connection;
    }

    public function query($sql) {
        try {
            $startTime = microtime(true);
            $result = $this->connection->query($sql);
            $endTime = microtime(true);
            
            // Log slow queries (over 1 second)
            $duration = $endTime - $startTime;
            if ($duration > 1) {
                error_log("Slow query ({$duration}s): {$sql}");
            }
            
            if ($result === false) {
                throw new Exception("Query failed: " . $this->connection->error);
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Query error: " . $e->getMessage());
            throw new Exception("Database query error occurred");
        }
    }

    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }

    public function beginTransaction() {
        return $this->connection->begin_transaction();
    }

    public function commit() {
        return $this->connection->commit();
    }

    public function rollback() {
        return $this->connection->rollback();
    }

    public function escapeString($string) {
        return $this->connection->real_escape_string($string);
    }

    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
            self::$instance = null;
        }
    }

    private function __clone() {}
    private function __wakeup() {}

    // Method to check database status
    public function getStatus() {
        $stats = [
            'threads' => $this->connection->query("SHOW STATUS LIKE 'Threads_%'")->fetch_all(MYSQLI_ASSOC),
            'connections' => $this->connection->query("SHOW STATUS LIKE 'Connections'")->fetch_all(MYSQLI_ASSOC),
            'uptime' => $this->connection->query("SHOW STATUS LIKE 'Uptime'")->fetch_all(MYSQLI_ASSOC)
        ];
        return $stats;
    }
}