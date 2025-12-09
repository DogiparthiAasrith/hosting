-- ============================================
-- DATABASE SCHEMA FOR SIMPLE PHP GUESTBOOK
-- ============================================
-- This file contains SQL commands to create the database and table
-- It will be executed automatically by the deploy.sh script

-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS simple_app_db;

-- Switch to use this database
USE simple_app_db;

-- Create the messages table
-- This table stores all guestbook entries
CREATE TABLE IF NOT EXISTS messages (
    -- id: Unique identifier for each message (auto-increments)
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- name: Person's name (up to 100 characters)
    name VARCHAR(100) NOT NULL,
    
    -- message: The actual message content (can be long text)
    message TEXT NOT NULL,
    
    -- created_at: Timestamp when the message was created
    -- DEFAULT CURRENT_TIMESTAMP means it automatically sets to current date/time
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Add an index on created_at for faster sorting
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create a dedicated MySQL user for the application
-- This is more secure than using the root user
CREATE USER IF NOT EXISTS 'guestbook_user'@'localhost' IDENTIFIED BY 'SecurePass123!';

-- Grant necessary permissions to the user
-- This user can only access the simple_app_db database
GRANT SELECT, INSERT, UPDATE, DELETE ON simple_app_db.* TO 'guestbook_user'@'localhost';

-- Apply the permission changes
FLUSH PRIVILEGES;

-- Optional: Insert some sample data for testing
-- You can remove these lines if you want to start with an empty guestbook
INSERT INTO messages (name, message) VALUES 
    ('John Doe', 'Hello! This is my first message in the guestbook.'),
    ('Jane Smith', 'Great application! Very simple and easy to use.'),
    ('Bob Wilson', 'Testing the guestbook functionality. Works perfectly!');
