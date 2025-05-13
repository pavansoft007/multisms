<?php
// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define CodeIgniter environment
define('ENVIRONMENT', 'development');
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

echo "<h1>Static Footer Menu Setup</h1>";

// Create static_footer_menu table if it doesn't exist
$createTable = "CREATE TABLE IF NOT EXISTS `static_footer_menu` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `menu_item` varchar(50) NOT NULL,
    `icon` varchar(50) NOT NULL,
    `url` varchar(255) NOT NULL,
    `status` tinyint(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($mysqli->query($createTable)) {
    echo "<p>Table static_footer_menu created or already exists.</p>";
} else {
    echo "<p>Error creating table: " . $mysqli->error . "</p>";
    exit;
}

// Clear existing data
$mysqli->query("TRUNCATE TABLE static_footer_menu");
echo "<p>Cleared existing data from static_footer_menu table.</p>";

// Insert static menu items
$menuItems = array(
    array('menu_item' => 'dashboard', 'icon' => 'fas fa-home', 'url' => 'dashboard'),
    array('menu_item' => 'mainmenu', 'icon' => 'fas fa-th-large', 'url' => 'mainmenu'),
    array('menu_item' => 'message', 'icon' => 'fas fa-envelope', 'url' => 'message'),
    array('menu_item' => 'profile', 'icon' => 'fas fa-user', 'url' => 'profile')
);

$stmt = $mysqli->prepare("INSERT INTO static_footer_menu (menu_item, icon, url, status) VALUES (?, ?, ?, 1)");

foreach ($menuItems as $item) {
    $stmt->bind_param("sss", $item['menu_item'], $item['icon'], $item['url']);
    if ($stmt->execute()) {
        echo "<p>Added menu item: " . $item['menu_item'] . "</p>";
    } else {
        echo "<p>Error adding menu item: " . $stmt->error . "</p>";
    }
}

$stmt->close();

// Drop the old dynamic footer menu table
$mysqli->query("DROP TABLE IF EXISTS footer_menu_config");
echo "<p>Dropped old footer_menu_config table.</p>";

echo "<p>Static footer menu setup completed successfully!</p>";
$mysqli->close(); 