<?php
// Script to set up mobile cards database table
// This script should be run once to create the necessary database table and default configurations

// Define BASEPATH to prevent direct access to CodeIgniter files
define('BASEPATH', true);

// Include database configuration
include 'application/config/database.php';

// Connect to database
$mysqli = new mysqli(
    $db['default']['hostname'],
    $db['default']['username'],
    $db['default']['password'],
    $db['default']['database']
);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Create the mobile_cards_config table if it doesn't exist
$create_table_sql = "
CREATE TABLE IF NOT EXISTS `mobile_cards_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `card_item` varchar(50) NOT NULL,
  `card_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";

if ($mysqli->query($create_table_sql) === TRUE) {
    echo "Table mobile_cards_config created successfully<br>";
} else {
    echo "Error creating table: " . $mysqli->error . "<br>";
}

// Check if there are any records in the table
$result = $mysqli->query("SELECT COUNT(*) as count FROM mobile_cards_config");
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    // Insert default card configurations for each role
    
    // Role ID 1: Superadmin
    $superadmin_cards = [
        ['role_id' => 1, 'card_item' => 'students', 'card_order' => 1],
        ['role_id' => 1, 'card_item' => 'attendance', 'card_order' => 2],
        ['role_id' => 1, 'card_item' => 'fees', 'card_order' => 3],
        ['role_id' => 1, 'card_item' => 'classes', 'card_order' => 4],
        ['role_id' => 1, 'card_item' => 'homework', 'card_order' => 5],
        ['role_id' => 1, 'card_item' => 'marks', 'card_order' => 6],
        ['role_id' => 1, 'card_item' => 'events', 'card_order' => 7],
        ['role_id' => 1, 'card_item' => 'reports', 'card_order' => 8],
        ['role_id' => 1, 'card_item' => 'profile', 'card_order' => 9],
        ['role_id' => 1, 'card_item' => 'settings', 'card_order' => 10]
    ];
    
    // Role ID 2: Admin
    $admin_cards = [
        ['role_id' => 2, 'card_item' => 'students', 'card_order' => 1],
        ['role_id' => 2, 'card_item' => 'attendance', 'card_order' => 2],
        ['role_id' => 2, 'card_item' => 'fees', 'card_order' => 3],
        ['role_id' => 2, 'card_item' => 'classes', 'card_order' => 4],
        ['role_id' => 2, 'card_item' => 'homework', 'card_order' => 5],
        ['role_id' => 2, 'card_item' => 'marks', 'card_order' => 6],
        ['role_id' => 2, 'card_item' => 'events', 'card_order' => 7],
        ['role_id' => 2, 'card_item' => 'reports', 'card_order' => 8],
        ['role_id' => 2, 'card_item' => 'profile', 'card_order' => 9],
        ['role_id' => 2, 'card_item' => 'settings', 'card_order' => 10]
    ];
    
    // Role ID 3: Teacher
    $teacher_cards = [
        ['role_id' => 3, 'card_item' => 'students', 'card_order' => 1],
        ['role_id' => 3, 'card_item' => 'attendance', 'card_order' => 2],
        ['role_id' => 3, 'card_item' => 'classes', 'card_order' => 3],
        ['role_id' => 3, 'card_item' => 'homework', 'card_order' => 4],
        ['role_id' => 3, 'card_item' => 'marks', 'card_order' => 5],
        ['role_id' => 3, 'card_item' => 'events', 'card_order' => 6],
        ['role_id' => 3, 'card_item' => 'profile', 'card_order' => 7],
        ['role_id' => 3, 'card_item' => 'settings', 'card_order' => 8]
    ];
    
    // Role ID 6: Parent
    $parent_cards = [
        ['role_id' => 6, 'card_item' => 'attendance', 'card_order' => 1],
        ['role_id' => 6, 'card_item' => 'fees', 'card_order' => 2],
        ['role_id' => 6, 'card_item' => 'homework', 'card_order' => 3],
        ['role_id' => 6, 'card_item' => 'marks', 'card_order' => 4],
        ['role_id' => 6, 'card_item' => 'events', 'card_order' => 5],
        ['role_id' => 6, 'card_item' => 'profile', 'card_order' => 6],
        ['role_id' => 6, 'card_item' => 'settings', 'card_order' => 7]
    ];
    
    // Role ID 7: Student
    $student_cards = [
        ['role_id' => 7, 'card_item' => 'attendance', 'card_order' => 1],
        ['role_id' => 7, 'card_item' => 'fees', 'card_order' => 2],
        ['role_id' => 7, 'card_item' => 'homework', 'card_order' => 3],
        ['role_id' => 7, 'card_item' => 'marks', 'card_order' => 4],
        ['role_id' => 7, 'card_item' => 'events', 'card_order' => 5],
        ['role_id' => 7, 'card_item' => 'profile', 'card_order' => 6],
        ['role_id' => 7, 'card_item' => 'settings', 'card_order' => 7]
    ];
    
    // Combine all cards
    $all_cards = array_merge($superadmin_cards, $admin_cards, $teacher_cards, $parent_cards, $student_cards);
    
    // Insert all cards
    $insert_stmt = $mysqli->prepare("INSERT INTO mobile_cards_config (role_id, card_item, card_order, status) VALUES (?, ?, ?, 1)");
    $insert_stmt->bind_param("isi", $role_id, $card_item, $card_order);
    
    $success_count = 0;
    $error_count = 0;
    
    foreach ($all_cards as $card) {
        $role_id = $card['role_id'];
        $card_item = $card['card_item'];
        $card_order = $card['card_order'];
        
        if ($insert_stmt->execute()) {
            $success_count++;
        } else {
            $error_count++;
            echo "Error inserting card: " . $mysqli->error . "<br>";
        }
    }
    
    echo "Inserted $success_count cards successfully<br>";
    if ($error_count > 0) {
        echo "Failed to insert $error_count cards<br>";
    }
    
    $insert_stmt->close();
} else {
    echo "Table already has data. Skipping default data insertion.<br>";
}

// Close connection
$mysqli->close();

echo "<br>Setup complete. <a href='mobilemain'>Go to Mobile Main Page</a>";
?>