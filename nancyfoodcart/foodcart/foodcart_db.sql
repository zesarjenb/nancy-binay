
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'staff', 'stock_personnel') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE expenses (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    description VARCHAR(255) NOT NULL
);ALTER TABLE sales ADD COLUMN date DATE; -- Adjust the column type if needed

CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_date DATE NOT NULL,     -- The date of the sale
    amount DECIMAL(10, 2) NOT NULL  -- The amount of the sale
);ALTER TABLE sales ADD COLUMN date DATE; -- Adjust the column type if needed

