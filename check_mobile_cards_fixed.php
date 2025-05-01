<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define ENVIRONMENT constant if not defined
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}

echo "<h1>Check Mobile Cards</h1>";

// Start session
session_start();

// Get user role ID
$role_id = isset($_SESSION['role_id']) ? (int)$_SESSION['role_id'] : 0;
$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

echo "<p>Session Status: " . ($logged_in ? 'Logged in' : 'Not logged in') . "</p>";
echo "<p>Current Role ID: $role_id</p>";

// Try to get database name from config
$config_file = file_get_contents('application/config/database.php');
preg_match("/['|\"]database['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
$db_name = isset($matches[3]) ? $matches[3] : 'multisms';

// Get database credentials
preg_match("/['|\"]username['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
$db_user = isset($matches[3]) ? $matches[3] : 'root';

preg_match("/['|\"]password['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
$db_pass = isset($matches[3]) ? $matches[3] : '';

try {
    // Connect to database
    $db = new PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>Database connected successfully</p>";
    
    // Check if the mobile_cards_config table exists
    $stmt = $db->query("SHOW TABLES LIKE 'mobile_cards_config'");
    $table_exists = ($stmt->rowCount() > 0);
    
    echo "<p>Table mobile_cards_config exists: " . ($table_exists ? 'Yes' : 'No') . "</p>";
    
    if ($table_exists) {
        // Get all records
        $stmt = $db->query("SELECT * FROM mobile_cards_config ORDER BY role_id, card_order");
        
        echo "<h2>All Records</h2>";
        echo "<p>Total records: " . $stmt->rowCount() . "</p>";
        
        if ($stmt->rowCount() > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Role ID</th><th>Card Item</th><th>Order</th><th>Status</th></tr>";
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['role_id']}</td>";
                echo "<td>{$row['card_item']}</td>";
                echo "<td>{$row['card_order']}</td>";
                echo "<td>{$row['status']}</td>";
                echo "</tr>";
            }
            
            echo "</table>";
        }
        
        // Get records by role
        $roles = array(
            1 => 'Admin',
            2 => 'Teacher',
            3 => 'Student',
            4 => 'Parent',
            5 => 'Accountant',
            6 => 'Librarian',
            7 => 'Staff'
        );
        
        foreach ($roles as $role_id => $role_name) {
            $stmt = $db->prepare("SELECT * FROM mobile_cards_config WHERE role_id = ? ORDER BY card_order");
            $stmt->execute([$role_id]);
            
            echo "<h2>Records for Role $role_id ($role_name)</h2>";
            echo "<p>Records found: " . $stmt->rowCount() . "</p>";
            
            if ($stmt->rowCount() > 0) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>ID</th><th>Card Item</th><th>Order</th><th>Status</th></tr>";
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['card_item']}</td>";
                    echo "<td>{$row['card_order']}</td>";
                    echo "<td>{$row['status']}</td>";
                    echo "</tr>";
                }
                
                echo "</table>";
            }
            
            // Get only enabled records
            $stmt = $db->prepare("SELECT * FROM mobile_cards_config WHERE role_id = ? AND status = 1 ORDER BY card_order");
            $stmt->execute([$role_id]);
            
            echo "<h3>Enabled Records for Role $role_id ($role_name)</h3>";
            echo "<p>Enabled records found: " . $stmt->rowCount() . "</p>";
            
            if ($stmt->rowCount() > 0) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>ID</th><th>Card Item</th><th>Order</th><th>Status</th></tr>";
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['card_item']}</td>";
                    echo "<td>{$row['card_order']}</td>";
                    echo "<td>{$row['status']}</td>";
                    echo "</tr>";
                }
                
                echo "</table>";
            }
        }
    }
    
    // Include the mobile_cards_helper.php file
    include_once('application/helpers/mobile_cards_helper.php');
    
    // Test the get_mobile_cards function
    if (function_exists('get_mobile_cards')) {
        echo "<h2>Testing get_mobile_cards Function</h2>";
        
        // Test for each role
        foreach ($roles as $role_id => $role_name) {
            echo "<h3>Cards for Role $role_id ($role_name)</h3>";
            
            try {
                $cards = get_mobile_cards($role_id);
                
                echo "<p>Cards found: " . count($cards) . "</p>";
                
                if (!empty($cards)) {
                    echo "<ul>";
                    foreach ($cards as $card) {
                        echo "<li>$card</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No cards found</p>";
                }
            } catch (Exception $e) {
                echo "<p>Error: " . $e->getMessage() . "</p>";
            }
        }
    } else {
        echo "<p>get_mobile_cards function not found</p>";
    }
    
    // Link to direct tools
    echo "<h2>Links</h2>";
    echo "<ul>";
    echo "<li><a href='direct_tools.php'>Direct Tools</a></li>";
    echo "<li><a href='direct_mobilemain.php'>Direct Mobile Main</a></li>";
    echo "<li><a href='direct_settings.php'>Direct Settings</a></li>";
    echo "<li><a href='index.php/mobilemain?force_mobile=1'>Original Mobile Main</a></li>";
    echo "</ul>";
    
} catch(PDOException $e) {
    echo "<p>Database Error: " . $e->getMessage() . "</p>";
}