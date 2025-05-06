<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define BASEPATH to bypass CodeIgniter security
define('BASEPATH', true);

// Database connection parameters
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'multisms';

try {
    // Connect to the database
    $db = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully<br>";
    
    // Check if theme_settings table exists and has data
    $stmt = $db->query('SHOW TABLES LIKE "theme_settings"');
    if($stmt->rowCount() > 0) {
        echo "theme_settings table exists<br>";
        
        // Check if there's data in the theme_settings table
        $stmt = $db->query('SELECT * FROM theme_settings');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "theme_settings table has " . count($rows) . " rows<br>";
        
        // Display the data
        echo "<pre>theme_settings data:<br>";
        print_r($rows);
        echo "</pre>";
    } else {
        echo "theme_settings table does NOT exist<br>";
    }
    
    // Check if global_settings table exists and has data
    $stmt = $db->query('SHOW TABLES LIKE "global_settings"');
    if($stmt->rowCount() > 0) {
        echo "global_settings table exists<br>";
        
        // Check if there's data in the global_settings table
        $stmt = $db->query('SELECT * FROM global_settings');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "global_settings table has " . count($rows) . " rows<br>";
        
        // Display the data
        echo "<pre>global_settings data:<br>";
        print_r($rows);
        echo "</pre>";
    } else {
        echo "global_settings table does NOT exist<br>";
    }
    
    // Check if global_images table exists and has data
    $stmt = $db->query('SHOW TABLES LIKE "global_images"');
    if($stmt->rowCount() > 0) {
        echo "global_images table exists<br>";
        
        // Check if there's data in the global_images table
        $stmt = $db->query('SELECT * FROM global_images');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "global_images table has " . count($rows) . " rows<br>";
        
        // Display the data
        echo "<pre>global_images data:<br>";
        print_r($rows);
        echo "</pre>";
    } else {
        echo "global_images table does NOT exist<br>";
    }
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>