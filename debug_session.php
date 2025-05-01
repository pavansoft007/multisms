<?php
// Initialize CodeIgniter
define('BASEPATH', true);
include_once 'application/config/constants.php';

// Start session
session_start();

// Output session data
echo "<h1>Session Debug</h1>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Check for specific session keys
echo "<h2>Important Session Keys</h2>";
echo "<ul>";
echo "<li>Role ID: " . (isset($_SESSION['role_id']) ? $_SESSION['role_id'] : 'Not set') . "</li>";
echo "<li>User ID: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not set') . "</li>";
echo "<li>Username: " . (isset($_SESSION['username']) ? $_SESSION['username'] : 'Not set') . "</li>";
echo "<li>Name: " . (isset($_SESSION['name']) ? $_SESSION['name'] : 'Not set') . "</li>";
echo "</ul>";

// Database connection
echo "<h2>Database Check</h2>";
try {
    // Try to get database name from config
    $config_file = file_get_contents('application/config/database.php');
    preg_match("/['|\"]database['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
    $db_name = isset($matches[3]) ? $matches[3] : 'multisms';
    
    echo "<p>Database name: $db_name</p>";
    
    $db = new PDO("mysql:host=localhost;dbname=$db_name", 'root', '');
    echo "<p>Database connected successfully</p>";
    
    // Check mobile_cards_config table
    $stmt = $db->query("SHOW TABLES LIKE 'mobile_cards_config'");
    echo "<p>Table mobile_cards_config exists: " . ($stmt->rowCount() > 0 ? 'Yes' : 'No') . "</p>";
    
    if ($stmt->rowCount() > 0) {
        // Get role ID from session
        $role_id = isset($_SESSION['role_id']) ? (int)$_SESSION['role_id'] : 0;
        
        // Get cards for this role
        $stmt = $db->prepare("SELECT * FROM mobile_cards_config WHERE role_id = ? AND status = 1 ORDER BY card_order ASC");
        $stmt->execute([$role_id]);
        
        echo "<h3>Cards for Role ID: $role_id</h3>";
        echo "<p>Records found: " . $stmt->rowCount() . "</p>";
        
        if ($stmt->rowCount() > 0) {
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Role ID</th><th>Card Item</th><th>Order</th><th>Status</th></tr>";
            
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['role_id']}</td>";
                echo "<td>{$row['card_item']}</td>";
                echo "<td>{$row['card_order']}</td>";
                echo "<td>{$row['status']}</td>";
                echo "</tr>";
            }
            
            echo "</table>";
        } else {
            echo "<p>No cards found for this role.</p>";
        }
    }
} catch(PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}