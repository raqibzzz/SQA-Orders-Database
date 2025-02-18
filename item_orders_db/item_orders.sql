SELECT * FROM inventory.item_orders;

GRANT ALL PRIVILEGES ON inventory.* TO 'xautosqa'@'localhost' IDENTIFIED BY 'x56789';
FLUSH PRIVILEGES;

USE inventory;

-- Create an index for REQ_PO_Number for efficient filtering
-- CREATE INDEX idx_req_po_number ON item_orders (REQ_PO_Number);

-- Create an index for Type, since it might be used for categorization or filtering
CREATE INDEX idx_type ON item_orders (Type);

-- Create an index for Model_Name to speed up searches
CREATE INDEX idx_model_name ON item_orders (Model_Name);

-- Create an index for Order_Date to optimize date-based filtering or sorting
CREATE INDEX idx_order_date ON item_orders (Order_Date);

-- Create an index for Order_Status to optimize status-based filtering
CREATE INDEX idx_order_status ON item_orders (Order_Status);

-- Optional: Create a composite index if you often query using multiple columns together
CREATE INDEX idx_composite_req_type_date ON item_orders (REQ_PO_Number, Type, Order_Date);


