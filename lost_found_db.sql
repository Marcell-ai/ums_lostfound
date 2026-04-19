-- ============================================================
-- UMS LOST & FOUND HUB - Database Schema
-- CP0025 Web Programming Group Assignment 2026
-- ============================================================

CREATE DATABASE IF NOT EXISTS lost_found_db;
USE lost_found_db;

-- Drop table if exists (for fresh setup)
DROP TABLE IF EXISTS items;

-- Main items table
CREATE TABLE items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    item_name   VARCHAR(255)    NOT NULL,
    description TEXT            NOT NULL,
    category    ENUM('Lost','Found') NOT NULL,
    location    VARCHAR(255)    NOT NULL,
    date_reported DATE          NOT NULL,
    contact_name  VARCHAR(150)  NOT NULL,
    contact_email VARCHAR(255)  NOT NULL,
    contact_phone VARCHAR(20)   NOT NULL,
    image_path  VARCHAR(500)    DEFAULT NULL,
    status      ENUM('Active','Resolved') DEFAULT 'Active',
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- Sample Data (for testing)
-- ============================================================

INSERT INTO items (item_name, description, category, location, date_reported, contact_name, contact_email, contact_phone, status) VALUES
('Blue Umbrella',     'Small navy blue umbrella with UMS logo on handle',           'Lost',  'Library, Level 2',           '2026-04-10', 'Ahmad Faris',     'ahmad.faris@student.ums.edu.my', '0123456789', 'Active'),
('Student ID Card',   'Student ID card belonging to someone from Faculty of Science','Found', 'Cafeteria near Block D',      '2026-04-11', 'Nurul Ain',       'nurul.ain@student.ums.edu.my',  '0198765432', 'Active'),
('Black Backpack',    'Large black backpack with a laptop and some books inside',    'Lost',  'Lecture Hall 3, Block B',    '2026-04-12', 'Tan Wei Liang',   'tan.wl@student.ums.edu.my',     '0112223344', 'Active'),
('Silver Watch',      'Silver Casio digital watch, scratched on left side',          'Found', 'Sports Complex',             '2026-04-13', 'Siti Rahmah',     'siti.r@student.ums.edu.my',     '0134455667', 'Active'),
('Spectacles',        'Black-framed spectacles in a brown leather case',             'Lost',  'Faculty of Business, Room 2','2026-04-14', 'Johari bin Ismail','johari.i@student.ums.edu.my',  '0167788990', 'Resolved');
