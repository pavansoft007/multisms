<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Restore Mobile Cards Helper</h1>";

// Restore the original helper
$helper_file = 'application/helpers/mobile_cards_helper.php';
$backup_file = 'application/helpers/mobile_cards_helper_backup_2025-04-27_19-57-47.php';

if (file_exists($backup_file)) {
    // Restore from backup
    if (copy($backup_file, $helper_file)) {
        echo "<p>Successfully restored the mobile_cards_helper from backup</p>";
    } else {
        echo "<p>Failed to restore the mobile_cards_helper from backup</p>";
        exit;
    }
    
    echo "<p>The mobile_cards_helper.php file has been restored to its original version.</p>";
} else {
    echo "<p>Backup file not found at $backup_file</p>";
}