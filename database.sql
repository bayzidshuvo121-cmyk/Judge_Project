-- CS Project Judging System - Database Setup
-- Run this in Aiven phpMyAdmin or any MySQL client

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('judge', 'admin') DEFAULT 'judge'
);

CREATE TABLE project_scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judge_id INT,
    group_members TEXT,
    group_number VARCHAR(50),
    project_title VARCHAR(255),
    score1 INT DEFAULT 0,
    score2 INT DEFAULT 0,
    score3 INT DEFAULT 0,
    score4 INT DEFAULT 0,
    total_score INT DEFAULT 0,
    comments TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, role) VALUES 
('judge1', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'judge'),
('judge2', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'judge'),
('judge3', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'judge'),
('judge4', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'judge'),
('admin', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');
