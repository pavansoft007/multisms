<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>PHP Error Check</h1>";

// Check PHP version
echo "<p>PHP Version: " . phpversion() . "</p>";

// Check error log path
echo "<p>Error Log Path: " . ini_get('error_log') . "</p>";

// Try to include the Mobilemain controller
echo "<h2>Testing Mobilemain Controller</h2>";
try {
    // Define BASEPATH to prevent direct access error
    define('BASEPATH', true);
    
    // Include the controller file
    include_once 'application/controllers/Mobilemain.php';
    
    echo "<p>Successfully included Mobilemain controller</p>";
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

// Check if the mobile_cards_config table exists
echo "<h2>Database Check</h2>";
try {
    // Try to get database name from config
    $config_file = file_get_contents('application/config/database.php');
    preg_match("/['|\"]database['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
    $db_name = isset($matches[3]) ? $matches[3] : 'multisms';
    
    echo "<p>Database name: $db_name</p>";
    
    // Get database credentials
    preg_match("/['|\"]username['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
    $db_user = isset($matches[3]) ? $matches[3] : 'root';
    
    preg_match("/['|\"]password['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
    $db_pass = isset($matches[3]) ? $matches[3] : '';
    
    echo "<p>Database user: $db_user</p>";
    
    $db = new PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_pass);
    echo "<p>Database connected successfully</p>";
    
    // Check mobile_cards_config table
    $stmt = $db->query("SHOW TABLES LIKE 'mobile_cards_config'");
    echo "<p>Table mobile_cards_config exists: " . ($stmt->rowCount() > 0 ? 'Yes' : 'No') . "</p>";
    
    if ($stmt->rowCount() > 0) {
        // Get all records
        $stmt = $db->query("SELECT * FROM mobile_cards_config LIMIT 5");
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
        }
    }
} catch(PDOException $e) {
    echo "<p>Database Error: " . $e->getMessage() . "</p>";
}

// Check for common issues in the Mobilemain controller
echo "<h2>Code Analysis</h2>";

$controller_file = file_get_contents('application/controllers/Mobilemain.php');

// Check for undefined methods
$methods_to_check = ['get_default_cards', 'get_card_details', 'is_mobile_device'];
foreach ($methods_to_check as $method) {
    if (strpos($controller_file, "function $method") !== false) {
        echo "<p>Method '$method' is defined in the controller.</p>";
    } else {
        echo "<p style='color:red'>Method '$method' is NOT defined in the controller!</p>";
    }
}

// Check for helper functions
if (strpos($controller_file, "load->helper('mobile_cards')") !== false) {
    echo "<p>Mobile cards helper is loaded.</p>";
} else {
    echo "<p style='color:red'>Mobile cards helper is NOT loaded!</p>";
}

// Check for common syntax errors
$syntax_errors = [];
if (substr_count($controller_file, '{') !== substr_count($controller_file, '}')) {
    $syntax_errors[] = "Mismatched curly braces";
}
if (substr_count($controller_file, '(') !== substr_count($controller_file, ')')) {
    $syntax_errors[] = "Mismatched parentheses";
}

if (empty($syntax_errors)) {
    echo "<p>No common syntax errors found.</p>";
} else {
    echo "<p style='color:red'>Potential syntax errors found: " . implode(", ", $syntax_errors) . "</p>";
}