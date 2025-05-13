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

echo "<h1>Removing Dynamic Footer Configuration</h1>";

// Drop the footer_menu_config table
if ($mysqli->query("DROP TABLE IF EXISTS `footer_menu_config`")) {
    echo "<p>Dropped footer_menu_config table.</p>";
} else {
    echo "<p>Error dropping footer_menu_config table: " . $mysqli->error . "</p>";
}

// Drop the role_footer_mapping table
if ($mysqli->query("DROP TABLE IF EXISTS `role_footer_mapping`")) {
    echo "<p>Dropped role_footer_mapping table.</p>";
} else {
    echo "<p>Error dropping role_footer_mapping table: " . $mysqli->error . "</p>";
}

// Remove related permissions
if ($mysqli->query("DELETE FROM `permission` WHERE `prefix` LIKE 'footer_%'")) {
    echo "<p>Removed footer-related permissions.</p>";
} else {
    echo "<p>Error removing permissions: " . $mysqli->error . "</p>";
}

// Clear cache directory
$cache_path = 'application/cache/';
if (is_dir($cache_path)) {
    $files = glob($cache_path . '*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "<p>Cleared cache directory.</p>";
}

echo "<p>Dynamic footer configuration removal completed!</p>";
$mysqli->close(); 