<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Restore Mobilemain Controller and Helper</h1>";

// Restore the original controller
$controller_file = 'application/controllers/Mobilemain.php';
$controller_backup_file = 'application/controllers/Mobilemain_backup_2025-04-27_19-44-10.php';

if (file_exists($controller_backup_file)) {
    // Restore from backup
    if (copy($controller_backup_file, $controller_file)) {
        echo "<p>Successfully restored the Mobilemain controller from backup</p>";
    } else {
        echo "<p>Failed to restore the Mobilemain controller from backup</p>";
        exit;
    }
} else {
    echo "<p>Controller backup file not found at $controller_backup_file</p>";
}

// Restore the original helper
$helper_file = 'application/helpers/mobile_cards_helper.php';
$helper_backup_file = 'application/helpers/mobile_cards_helper_backup_2025-04-27_19-44-10.php';

if (file_exists($helper_backup_file)) {
    // Restore from backup
    if (copy($helper_backup_file, $helper_file)) {
        echo "<p>Successfully restored the mobile_cards_helper from backup</p>";
    } else {
        echo "<p>Failed to restore the mobile_cards_helper from backup</p>";
        exit;
    }
} else {
    echo "<p>Helper backup file not found at $helper_backup_file</p>";
}

echo "<p>The Mobilemain controller and mobile_cards_helper have been restored to their original versions.</p>";
echo "<p>You can now <a href='index.php/mobilemain?force_mobile=1'>test the mobilemain page</a>.</p>";