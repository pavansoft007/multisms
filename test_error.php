<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "PHP is working correctly<br>";

// Try to connect to the database
try {
    $db = new PDO('mysql:host=localhost;dbname=multisms', 'root', '');
    echo "Connected to database successfully<br>";
    
    // Check if theme_settings table exists
    $stmt = $db->query('SHOW TABLES LIKE "theme_settings"');
    if($stmt->rowCount() > 0) {
        echo "theme_settings table exists<br>";
        
        // Check if there are records in the theme_settings table
        $stmt = $db->query('SELECT * FROM theme_settings');
        echo "Number of records in theme_settings: " . $stmt->rowCount() . "<br>";
    } else {
        echo "theme_settings table does not exist<br>";
    }
    
    // Check if global_settings table exists
    $stmt = $db->query('SHOW TABLES LIKE "global_settings"');
    if($stmt->rowCount() > 0) {
        echo "global_settings table exists<br>";
        
        // Check if there are records in the global_settings table
        $stmt = $db->query('SELECT * FROM global_settings');
        echo "Number of records in global_settings: " . $stmt->rowCount() . "<br>";
    } else {
        echo "global_settings table does not exist<br>";
    }
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "<br>";
}
?>