<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<h1>Error: Not logged in</h1>";
    echo "<p>Please <a href='check_session.php'>set a session</a> first.</p>";
    exit;
}

// Get user role ID
$role_id = isset($_SESSION['role_id']) ? (int)$_SESSION['role_id'] : 0;

echo "<h1>Mobile Cards Settings (Direct Version)</h1>";
echo "<p>User Role ID: $role_id</p>";

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
    
    // Check if the mobile_cards_config table exists
    $stmt = $db->query("SHOW TABLES LIKE 'mobile_cards_config'");
    $table_exists = ($stmt->rowCount() > 0);
    
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
    }
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
        $selected_role_id = (int)$_POST['role_id'];
        $card_items = isset($_POST['card_items']) ? $_POST['card_items'] : array();
        
        // Start a transaction
        $db->beginTransaction();
        
        // Delete existing configuration for this role
        $stmt = $db->prepare("DELETE FROM mobile_cards_config WHERE role_id = ?");
        $stmt->execute([$selected_role_id]);
        
        // Insert new configuration
        if (!empty($card_items)) {
            $order = 1;
            foreach ($card_items as $card_item) {
                $stmt = $db->prepare("INSERT INTO mobile_cards_config (role_id, card_item, card_order, status) VALUES (?, ?, ?, 1)");
                $stmt->execute([$selected_role_id, $card_item, $order]);
                $order++;
            }
        } else {
            // Insert a dummy record to indicate that this role has been configured
            // This prevents the system from using default cards
            $stmt = $db->prepare("INSERT INTO mobile_cards_config (role_id, card_item, card_order, status) VALUES (?, 'none', 1, 0)");
            $stmt->execute([$selected_role_id]);
        }
        
        // Commit the transaction
        $db->commit();
        
        echo "<p style='color: green; font-weight: bold;'>Settings saved successfully!</p>";
    }
    
    // Get all roles
    $roles = array(
        1 => 'Admin',
        2 => 'Teacher',
        3 => 'Student',
        4 => 'Parent',
        5 => 'Accountant',
        6 => 'Librarian',
        7 => 'Staff'
    );
    
    // Get all available card items
    $all_cards = array(
        'students' => 'Students',
        'attendance' => 'Attendance',
        'fees' => 'Fees',
        'classes' => 'Classes',
        'homework' => 'Homework',
        'marks' => 'Marks',
        'events' => 'Events',
        'reports' => 'Reports',
        'profile' => 'Profile',
        'settings' => 'Settings'
    );
    
    // Get selected role ID from query string or form
    $selected_role_id = isset($_GET['role_id']) ? (int)$_GET['role_id'] : (isset($_POST['role_id']) ? (int)$_POST['role_id'] : 1);
    
    // Get selected card items for this role
    $stmt = $db->prepare("SELECT card_item FROM mobile_cards_config WHERE role_id = ? AND status = 1 ORDER BY card_order ASC");
    $stmt->execute([$selected_role_id]);
    
    $selected_items = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $selected_items[] = $row['card_item'];
    }
    
    // Display the form
    echo "<form method='post' action='direct_settings.php'>";
    
    // Role selection
    echo "<div style='margin-bottom: 20px;'>";
    echo "<label for='role_id'>Select Role:</label>";
    echo "<select name='role_id' id='role_id' onchange='this.form.submit()'>";
    
    foreach ($roles as $id => $name) {
        $selected = ($id == $selected_role_id) ? 'selected' : '';
        echo "<option value='$id' $selected>$id - $name</option>";
    }
    
    echo "</select>";
    echo "</div>";
    
    // Card selection
    echo "<div style='margin-bottom: 20px;'>";
    echo "<h2>Select Cards for " . $roles[$selected_role_id] . "</h2>";
    
    echo "<div style='display: flex; flex-wrap: wrap;'>";
    
    foreach ($all_cards as $card_item => $card_title) {
        $checked = in_array($card_item, $selected_items) ? 'checked' : '';
        
        echo "<div style='width: 200px; margin: 10px; padding: 15px; border-radius: 5px; background-color: #f0f0f0;'>";
        echo "<label>";
        echo "<input type='checkbox' name='card_items[]' value='$card_item' $checked>";
        echo " $card_title";
        echo "</label>";
        echo "</div>";
    }
    
    echo "</div>";
    echo "</div>";
    
    // Submit button
    echo "<div style='margin-top: 20px;'>";
    echo "<input type='submit' name='save' value='Save Settings'>";
    echo "</div>";
    
    echo "</form>";
    
    // Link to main page
    echo "<p><a href='direct_mobilemain.php'>View Mobile Main Page</a></p>";
    
    // Link to check session
    echo "<p><a href='check_session.php'>Change Session</a></p>";
    
    // Link to check database
    echo "<p><a href='check_mobile_cards.php'>Check Database</a></p>";
    
    // Display current configuration
    echo "<h2>Current Configuration for " . $roles[$selected_role_id] . "</h2>";
    
    $stmt = $db->prepare("SELECT * FROM mobile_cards_config WHERE role_id = ? ORDER BY card_order ASC");
    $stmt->execute([$selected_role_id]);
    
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
    } else {
        echo "<p>No configuration found for this role</p>";
    }
    
} catch(PDOException $e) {
    echo "<p>Database Error: " . $e->getMessage() . "</p>";
}