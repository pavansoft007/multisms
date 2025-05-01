<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Direct Database Check</h1>";

// Try to get database name from config
$config_file = file_get_contents('application/config/database.php');
preg_match("/['|\"]database['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
$db_name = isset($matches[3]) ? $matches[3] : 'multisms';

// Get database credentials
preg_match("/['|\"]username['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
$db_user = isset($matches[3]) ? $matches[3] : 'root';

preg_match("/['|\"]password['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
$db_pass = isset($matches[3]) ? $matches[3] : '';

echo "<p>Database: $db_name, User: $db_user</p>";

try {
    // Connect to database
    $db = new PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>Database connected successfully</p>";
    
    // Check mobile_cards_config table
    $stmt = $db->query("SHOW TABLES LIKE 'mobile_cards_config'");
    $table_exists = ($stmt->rowCount() > 0);
    
    echo "<p>Table mobile_cards_config exists: " . ($table_exists ? 'Yes' : 'No') . "</p>";
    
    if ($table_exists) {
        // Check table structure
        $stmt = $db->query("DESCRIBE mobile_cards_config");
        
        echo "<h2>Table Structure</h2>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['Field']}</td>";
            echo "<td>{$row['Type']}</td>";
            echo "<td>{$row['Null']}</td>";
            echo "<td>{$row['Key']}</td>";
            echo "<td>{$row['Default']}</td>";
            echo "<td>{$row['Extra']}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
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
    
    // Link to main page
    echo "<p><a href='direct_mobilemain.php'>View Mobile Main Page</a></p>";
    
    // Link to settings page
    echo "<p><a href='direct_settings.php'>Configure Cards</a></p>";
    
    // Link to check session
    echo "<p><a href='check_session.php'>Change Session</a></p>";
    
} catch(PDOException $e) {
    echo "<p>Database Error: " . $e->getMessage() . "</p>";
}