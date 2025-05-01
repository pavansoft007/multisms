<?php
// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'multisms';

// Try to connect to the database
try {
    $conn = new mysqli($hostname, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    echo "Database Connection Successful!<br>";
    
    // Check if theme_settings table exists
    $result = $conn->query("SHOW TABLES LIKE 'theme_settings'");
    if ($result->num_rows > 0) {
        echo "theme_settings table exists.<br>";
    } else {
        echo "theme_settings table does not exist!<br>";
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>