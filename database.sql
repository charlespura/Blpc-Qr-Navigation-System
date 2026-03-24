-- File: database.sql
-- Run this in phpMyAdmin or MySQL command line

CREATE DATABASE IF NOT EXISTS blpc;
USE blpc;

CREATE TABLE residents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    map_link TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample data
INSERT INTO residents (name, address, map_link) VALUES
('Juan Dela Cruz', 'Block 5 Lot 10, Phase 2', 'https://maps.google.com/?q=14.5995,120.9842'),
('Maria Santos', 'Block 2 Lot 8, Phase 1', 'https://maps.google.com/?q=14.6000,120.9850'),
('Pedro Reyes', 'Block 8 Lot 3, Phase 3', 'https://maps.google.com/?q=14.5980,120.9830'),
('Ana Flores', 'Block 1 Lot 12, Phase 1', 'https://maps.google.com/?q=14.6010,120.9860');