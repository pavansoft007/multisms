<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Restore .htaccess</h1>";

// Restore the original .htaccess
$htaccess_file = '.htaccess';
$backup_file = '.htaccess_backup_2025-04-27_23-23-58';

if (file_exists($backup_file)) {
    // Restore from backup
    if (copy($backup_file, $htaccess_file)) {
        echo "<p>Successfully restored the .htaccess from backup</p>";
    } else {
        echo "<p>Failed to restore the .htaccess from backup</p>";
        exit;
    }
    
    echo "<p>The .htaccess file has been restored to its original version.</p>";
} else {
    echo "<p>Backup file not found at $backup_file</p>";
}