<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Restore Mobilemain Controller</h1>";

// Restore the original controller
$controller_file = 'application/controllers/Mobilemain.php';
$backup_file = 'application/controllers/Mobilemain_backup_2025-04-27_19-35-03.php';

if (file_exists($backup_file)) {
    // Restore from backup
    if (copy($backup_file, $controller_file)) {
        echo "<p>Successfully restored the Mobilemain controller from backup</p>";
    } else {
        echo "<p>Failed to restore the Mobilemain controller from backup</p>";
        exit;
    }
    
    echo "<p>The Mobilemain controller has been restored to its original version.</p>";
    echo "<p>You can now <a href='index.php/mobilemain?force_mobile=1'>test the mobilemain page</a>.</p>";
} else {
    echo "<p>Backup file not found at $backup_file</p>";
}