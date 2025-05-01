<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Mobile Cards Database Check</h1>";

// Start session to get role_id
session_start();
$current_role_id = isset($_SESSION['role_id']) ? (int)$_SESSION['role_id'] : 0;
echo "<p>Current session role ID: $current_role_id</p>";

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
    echo "<p>Table mobile_cards_config exists: " . ($stmt->rowCount() > 0 ? 'Yes' : 'No') . "</p>";
    
    if ($stmt->rowCount() > 0) {
        // Get all records
        $stmt = $db->query("SELECT * FROM mobile_cards_config ORDER BY role_id, card_order");
        echo "<h2>All Records in mobile_cards_config</h2>";
        echo "<p>Total records: " . $stmt->rowCount() . "</p>";
        
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
        
        // Get records for current role
        if ($current_role_id > 0) {
            $stmt = $db->prepare("SELECT * FROM mobile_cards_config WHERE role_id = ? ORDER BY card_order");
            $stmt->execute([$current_role_id]);
            
            echo "<h2>Records for Current Role ID: $current_role_id</h2>";
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
            
            // Get only enabled records for current role
            $stmt = $db->prepare("SELECT * FROM mobile_cards_config WHERE role_id = ? AND status = 1 ORDER BY card_order");
            $stmt->execute([$current_role_id]);
            
            echo "<h2>Enabled Records for Current Role ID: $current_role_id</h2>";
            echo "<p>Enabled records found: " . $stmt->rowCount() . "</p>";
            
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
    }
    
    // Test the get_mobile_cards function directly
    echo "<h2>Testing get_mobile_cards Function</h2>";
    
    // Define BASEPATH to prevent direct access error
    if (!defined('BASEPATH')) define('BASEPATH', true);
    
    // Include the helper file
    include_once 'application/helpers/mobile_cards_helper.php';
    
    // We need to define the base_url function since it's used in the helper
    if (!function_exists('base_url')) {
        function base_url($uri = '') {
            return 'http://localhost/multisms/' . $uri;
        }
    }
    
    // Create a mock CI instance
    class MockDB {
        public function table_exists($table) {
            return true;
        }
        
        public function select($field) {
            return $this;
        }
        
        public function from($table) {
            return $this;
        }
        
        public function where($field, $value = null) {
            return $this;
        }
        
        public function order_by($field, $order = 'ASC') {
            return $this;
        }
        
        public function get() {
            global $db, $current_role_id;
            
            // Actually query the database
            $stmt = $db->prepare("SELECT card_item FROM mobile_cards_config WHERE role_id = ? AND status = 1 ORDER BY card_order ASC");
            $stmt->execute([$current_role_id]);
            
            $result = new stdClass();
            $result->num_rows = $stmt->rowCount();
            $result->result_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        }
        
        public function last_query() {
            global $current_role_id;
            return "SELECT card_item FROM mobile_cards_config WHERE role_id = $current_role_id AND status = 1 ORDER BY card_order ASC";
        }
    }
    
    class MockCI {
        public $db;
        
        public function __construct() {
            $this->db = new MockDB();
        }
    }
    
    $CI = new MockCI();
    
    // Override get_instance
    function &get_instance() {
        global $CI;
        return $CI;
    }
    
    // Test the function
    if (function_exists('get_mobile_cards')) {
        $cards = get_mobile_cards($current_role_id);
        
        echo "<p>Cards returned by get_mobile_cards for role $current_role_id:</p>";
        echo "<ul>";
        foreach ($cards as $card) {
            echo "<li>$card</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Function get_mobile_cards not found!</p>";
    }
    
} catch(PDOException $e) {
    echo "<p>Database Error: " . $e->getMessage() . "</p>";
}