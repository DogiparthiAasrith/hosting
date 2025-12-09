<?php
/**
 * DATABASE CONFIGURATION FILE
 * 
 * This file contains all the settings needed to connect to MySQL database.
 * 
 * IMPORTANT: After deploying to Lightsail, you may need to update these values
 * based on your MySQL setup.
 */

// Database connection settings
$host = 'localhost';           // MySQL server address (localhost means same server)
$username = 'guestbook_user';  // MySQL username for the application
$password = 'SecurePass123!';  // MySQL password (CHANGE THIS!)
$database = 'simple_app_db';   // Name of the database

// Create connection to MySQL database
// mysqli is a PHP extension for connecting to MySQL
$conn = new mysqli($host, $username, $password, $database);

// Check if connection was successful
if ($conn->connect_error) {
    // If connection fails, stop the script and show error
    // In production, you might want to show a generic error message instead
    die("Connection failed: " . $conn->connect_error);
}

// Set character encoding to UTF-8
// This ensures proper handling of special characters and emojis
$conn->set_charset("utf8mb4");

// Optional: You can uncomment the line below to see a success message during testing
// echo "Connected successfully to database!";
?>
