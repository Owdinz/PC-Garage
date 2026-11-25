
-- PC-Garage BUG-013
-- Part 2: COD Checkout Integration
-- For databases that have NOT yet received
-- the BUG-013 checkout schema changes.
--
-- Apply once after backing up pc_garage.
-- Do not run on the already-migrated local database.

CREATE TABLE IF NOT EXISTS order_shipping_details (
    shipping_detail_id INT NOT NULL AUTO_INCREMENT,
    order_id INT NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    contact_number VARCHAR(30) NOT NULL,
    email VARCHAR(255) NOT NULL,
    shipping_address TEXT NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    delivery_notes TEXT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (shipping_detail_id),

    UNIQUE KEY unique_order_shipping (order_id),

    CONSTRAINT fk_order_shipping_order
        FOREIGN KEY (order_id)
        REFERENCES orders(order_id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Run this ALTER TABLE only if checkout_request_id
-- does not already exist in the orders table.

ALTER TABLE orders
    ADD COLUMN checkout_request_id CHAR(64) NULL,
    ADD UNIQUE KEY unique_checkout_request
        (checkout_request_id);
