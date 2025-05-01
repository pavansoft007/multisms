<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define ENVIRONMENT constant if not defined
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}

// Define BASEPATH constant if not defined
if (!defined('BASEPATH')) {
    define('BASEPATH', dirname(__FILE__) . '/');
}

// Start session
session_start();

// Get user role ID
$role_id = isset($_SESSION['role_id']) ? (int)$_SESSION['role_id'] : 0;
$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$user_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'User';

echo "<h1>Direct Access Diagnostic Tool</h1>";

echo "<h2>Session Information</h2>";
echo "<p>Logged in: " . ($logged_in ? 'Yes' : 'No') . "</p>";
echo "<p>Role ID: $role_id</p>";
echo "<p>User Name: $user_name</p>";

echo "<h2>Server Information</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "</p>";
echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";

echo "<h2>CodeIgniter Configuration</h2>";
echo "<p>BASEPATH: " . BASEPATH . "</p>";
echo "<p>ENVIRONMENT: " . ENVIRONMENT . "</p>";

// Check if the Mobilemain controller exists
$controller_file = 'application/controllers/Mobilemain.php';
echo "<p>Mobilemain controller exists: " . (file_exists($controller_file) ? 'Yes' : 'No') . "</p>";

// Check if the mobile_cards_helper.php file exists
$helper_file = 'application/helpers/mobile_cards_helper.php';
echo "<p>Mobile cards helper exists: " . (file_exists($helper_file) ? 'Yes' : 'No') . "</p>";

// Check if the mobilemain view exists
$view_file = 'application/views/mobilemain.php';
echo "<p>Mobilemain view exists: " . (file_exists($view_file) ? 'Yes' : 'No') . "</p>";

// Check if the MY_Controller.php file exists
$my_controller_file = 'application/core/MY_Controller.php';
echo "<p>MY_Controller exists: " . (file_exists($my_controller_file) ? 'Yes' : 'No') . "</p>";

// Check if the .htaccess file exists
$htaccess_file = '.htaccess';
echo "<p>.htaccess exists: " . (file_exists($htaccess_file) ? 'Yes' : 'No') . "</p>";

// Check if mod_rewrite is enabled
echo "<p>mod_rewrite enabled: " . (in_array('mod_rewrite', apache_get_modules()) ? 'Yes' : 'No') . "</p>";

echo "<h2>Test Links</h2>";
echo "<ul>";
echo "<li><a href='index.php/mobilemain?force_mobile=1'>Original Mobile Main (with index.php)</a></li>";
echo "<li><a href='mobilemain?force_mobile=1'>Mobile Main (without index.php)</a></li>";
echo "<li><a href='mobilemain.php'>Mobile Main Redirect</a></li>";
echo "<li><a href='direct_mobilemain.php'>Direct Mobile Main</a></li>";
echo "<li><a href='direct_tools.php'>Direct Tools</a></li>";
echo "<li><a href='check_session.php'>Session Management</a></li>";
echo "</ul>";

echo "<h2>Fix Links</h2>";
echo "<ul>";
echo "<li><a href='fix_helper_num_rows.php'>Fix Helper (num_rows)</a></li>";
echo "<li><a href='fix_helper.php'>Fix Helper (ENVIRONMENT)</a></li>";
echo "<li><a href='replace_mobilemain.php'>Replace Mobilemain</a></li>";
echo "</ul>";

// Try to include the index.php file directly
echo "<h2>Direct Index.php Test</h2>";
echo "<p>This will attempt to include the index.php file directly to see if it works.</p>";
echo "<p>Note: This may cause errors or unexpected behavior.</p>";

// Create a form to test direct access
echo "<h2>Direct Controller Access Test</h2>";
echo "<form method='post' action='direct_access.php'>";
echo "<p>This will attempt to directly access the Mobilemain controller.</p>";
echo "<input type='submit' name='test_controller' value='Test Controller'>";
echo "</form>";

// Test direct controller access
if (isset($_POST['test_controller'])) {
    echo "<h3>Controller Test Results</h3>";
    
    try {
        // Include the necessary files
        require_once 'application/core/MY_Controller.php';
        require_once 'application/helpers/mobile_cards_helper.php';
        require_once 'application/controllers/Mobilemain.php';
        
        echo "<p>Successfully included controller files</p>";
        
        // Create an instance of the controller
        $controller = new Mobilemain();
        
        echo "<p>Successfully created controller instance</p>";
        
        // Call the index method
        $controller->index();
        
        echo "<p>Successfully called index method</p>";
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
}

// Create a form to test direct database access
echo "<h2>Direct Database Access Test</h2>";
echo "<form method='post' action='direct_access.php'>";
echo "<p>This will attempt to directly access the database.</p>";
echo "<input type='submit' name='test_database' value='Test Database'>";
echo "</form>";

// Test direct database access
if (isset($_POST['test_database'])) {
    echo "<h3>Database Test Results</h3>";
    
    try {
        // Try to get database name from config
        $config_file = file_get_contents('application/config/database.php');
        preg_match("/['|\"]database['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
        $db_name = isset($matches[3]) ? $matches[3] : 'multisms';
        
        // Get database credentials
        preg_match("/['|\"]username['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
        $db_user = isset($matches[3]) ? $matches[3] : 'root';
        
        preg_match("/['|\"]password['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
        $db_pass = isset($matches[3]) ? $matches[3] : '';
        
        echo "<p>Database Name: $db_name</p>";
        echo "<p>Database User: $db_user</p>";
        
        // Connect to database
        $db = new PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "<p>Successfully connected to database</p>";
        
        // Check if the mobile_cards_config table exists
        $stmt = $db->query("SHOW TABLES LIKE 'mobile_cards_config'");
        $table_exists = ($stmt->rowCount() > 0);
        
        echo "<p>Table mobile_cards_config exists: " . ($table_exists ? 'Yes' : 'No') . "</p>";
        
        if ($table_exists) {
            // Get all records
            $stmt = $db->query("SELECT * FROM mobile_cards_config ORDER BY role_id, card_order");
            
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
        }
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
}