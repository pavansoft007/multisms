<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Fix Mobile Cards Database</h1>";

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
    $db = new PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_pass);
    echo "<p>Database connected successfully</p>";
    
    // Check mobile_cards_config table
    $stmt = $db->query("SHOW TABLES LIKE 'mobile_cards_config'");
    $table_exists = ($stmt->rowCount() > 0);
    echo "<p>Table mobile_cards_config exists: " . ($table_exists ? 'Yes' : 'No') . "</p>";
    
    if (!$table_exists) {
        // Create the table
        $sql = "CREATE TABLE `mobile_cards_config` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `role_id` int(11) NOT NULL DEFAULT 0,
            `card_item` varchar(50) NOT NULL,
            `card_order` int(11) NOT NULL DEFAULT 0,
            `status` tinyint(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (`id`),
            KEY `role_id` (`role_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        $result = $db->exec($sql);
        if ($result !== false) {
            echo "<p>Created mobile_cards_config table</p>";
        } else {
            echo "<p>Failed to create mobile_cards_config table</p>";
            print_r($db->errorInfo());
            exit;
        }
    } else {
        // Check if the table has the correct structure
        $stmt = $db->query("DESCRIBE mobile_cards_config");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $expected_columns = array('id', 'role_id', 'card_item', 'card_order', 'status');
        $missing_columns = array_diff($expected_columns, $columns);
        
        if (!empty($missing_columns)) {
            echo "<p>Table mobile_cards_config is missing columns: " . implode(', ', $missing_columns) . "</p>";
            
            // Add missing columns
            foreach ($missing_columns as $column) {
                switch ($column) {
                    case 'id':
                        $sql = "ALTER TABLE mobile_cards_config ADD COLUMN `id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
                        break;
                    case 'role_id':
                        $sql = "ALTER TABLE mobile_cards_config ADD COLUMN `role_id` int(11) NOT NULL DEFAULT 0 AFTER `id`";
                        break;
                    case 'card_item':
                        $sql = "ALTER TABLE mobile_cards_config ADD COLUMN `card_item` varchar(50) NOT NULL AFTER `role_id`";
                        break;
                    case 'card_order':
                        $sql = "ALTER TABLE mobile_cards_config ADD COLUMN `card_order` int(11) NOT NULL DEFAULT 0 AFTER `card_item`";
                        break;
                    case 'status':
                        $sql = "ALTER TABLE mobile_cards_config ADD COLUMN `status` tinyint(1) NOT NULL DEFAULT 1 AFTER `card_order`";
                        break;
                }
                
                $result = $db->exec($sql);
                if ($result !== false) {
                    echo "<p>Added column $column to mobile_cards_config table</p>";
                } else {
                    echo "<p>Failed to add column $column to mobile_cards_config table</p>";
                    print_r($db->errorInfo());
                }
            }
        } else {
            echo "<p>Table mobile_cards_config has the correct structure</p>";
        }
    }
    
    // Check if there are any records in the table
    $stmt = $db->query("SELECT COUNT(*) FROM mobile_cards_config");
    $count = $stmt->fetchColumn();
    echo "<p>Records in mobile_cards_config: $count</p>";
    
    // Add default records for each role if none exist
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
        $stmt = $db->prepare("SELECT COUNT(*) FROM mobile_cards_config WHERE role_id = ?");
        $stmt->execute([$role_id]);
        $role_count = $stmt->fetchColumn();
        
        echo "<p>Records for role $role_id ($role_name): $role_count</p>";
        
        if ($role_count == 0) {
            // Add default cards for this role
            $cards = array();
            
            // Default cards for all roles
            $default_cards = array('profile', 'settings');
            
            // Role-specific default cards
            if ($role_id == 7 || $role_id == 3) { // Student or Staff
                $cards = array_merge(array('attendance', 'fees', 'homework', 'marks', 'events'), $default_cards);
            } else if ($role_id == 4 || $role_id == 6) { // Parent or Librarian
                $cards = array_merge(array('attendance', 'fees', 'homework', 'marks', 'events'), $default_cards);
            } else if ($role_id == 2) { // Teacher
                $cards = array_merge(array('students', 'attendance', 'classes', 'homework', 'marks', 'events'), $default_cards);
            } else { // Admin, Accountant, and others
                $cards = array_merge(array('students', 'attendance', 'fees', 'classes', 'homework', 'marks', 'events', 'reports'), $default_cards);
            }
            
            echo "<p>Adding default cards for role $role_id ($role_name): " . implode(', ', $cards) . "</p>";
            
            // Insert the cards
            $order = 1;
            foreach ($cards as $card) {
                $stmt = $db->prepare("INSERT INTO mobile_cards_config (role_id, card_item, card_order, status) VALUES (?, ?, ?, 1)");
                $result = $stmt->execute([$role_id, $card, $order]);
                
                if ($result) {
                    echo "<p>Added card $card for role $role_id with order $order</p>";
                } else {
                    echo "<p>Failed to add card $card for role $role_id</p>";
                    print_r($stmt->errorInfo());
                }
                
                $order++;
            }
        }
    }
    
    echo "<h2>Database Fix Complete</h2>";
    echo "<p>You can now <a href='check_session.php'>set a session</a> and <a href='index.php/mobilemain?force_mobile=1'>test the mobilemain page</a>.</p>";
    
} catch(PDOException $e) {
    echo "<p>Database Error: " . $e->getMessage() . "</p>";
}