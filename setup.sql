CREATE DATABASE IF NOT EXISTS slitting_db;
USE slitting_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    department ENUM('Production','Slitting','Admin')
);

CREATE TABLE production (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_no VARCHAR(50), job_date DATE, job_code VARCHAR(50), size VARCHAR(20),
    coil1 DECIMAL(10,2), coil2 DECIMAL(10,2), total_weight DECIMAL(10,2),
    total_rolls INT, per_roll_meter DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE slitting (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sr_no INT, size VARCHAR(20), gross_weight DECIMAL(10,2), core_weight DECIMAL(10,2),
    net_weight DECIMAL(10,2), micron INT, meter DECIMAL(12,2), entry_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username,password,department) VALUES
('admin','$2y$10$3o6j1r5r2xY7vF9kLmN5Qe9z1b2c3d4e5f6g7h8i9j0k1l2m3n4o5','Admin'),
('production','$2y$10$3o6j1r5r2xY7vF9kLmN5Qe9z1b2c3d4e5f6g7h8i9j0k1l2m3n4o5','Production'),
('slitting','$2y$10$3o6j1r5r2xY7vF9kLmN5Qe9z1b2c3d4e5f6g7h8i9j0k1l2m3n4o5','Slitting');
