<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Fix Mobile Cards Helper</h1>";

// Check if the helper file exists
$helper_file = 'application/helpers/mobile_cards_helper.php';

if (!file_exists($helper_file)) {
    echo "<p>Error: Mobile cards helper file not found</p>";
    exit;
}

// Create a backup of the original file
$backup_file = 'application/helpers/mobile_cards_helper_backup_' . date('Y-m-d_H-i-s') . '.php';
if (copy($helper_file, $backup_file)) {
    echo "<p>Created backup of original helper at $backup_file</p>";
} else {
    echo "<p>Failed to create backup of original helper</p>";
    exit;
}

// Read the original file
$original_content = file_get_contents($helper_file);

// Replace all occurrences of ENVIRONMENT with a check
$new_content = str_replace(
    'if (ENVIRONMENT !== \'production\') {',
    'if (defined(\'ENVIRONMENT\') && ENVIRONMENT !== \'production\') {',
    $original_content
);

// Write the new content to the file
if (file_put_contents($helper_file, $new_content)) {
    echo "<p>Successfully updated the mobile_cards_helper.php file</p>";
} else {
    echo "<p>Failed to update the mobile_cards_helper.php file</p>";
    exit;
}

echo "<p>The mobile_cards_helper.php file has been updated to check if ENVIRONMENT is defined.</p>";
echo "<p>You can now <a href='check_mobile_cards_fixed.php'>test the helper</a>.</p>";
echo "<p>If you encounter any issues, you can restore the backup by copying $backup_file back to $helper_file.</p>";

// Create a restore script
$restore_script = <<<EOT
<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Restore Mobile Cards Helper</h1>";

// Restore the original helper
\$helper_file = 'application/helpers/mobile_cards_helper.php';
\$backup_file = '$backup_file';

if (file_exists(\$backup_file)) {
    // Restore from backup
    if (copy(\$backup_file, \$helper_file)) {
        echo "<p>Successfully restored the mobile_cards_helper from backup</p>";
    } else {
        echo "<p>Failed to restore the mobile_cards_helper from backup</p>";
        exit;
    }
    
    echo "<p>The mobile_cards_helper.php file has been restored to its original version.</p>";
} else {
    echo "<p>Backup file not found at \$backup_file</p>";
}
EOT;

$restore_file = 'restore_helper.php';
if (file_put_contents($restore_file, $restore_script)) {
    echo "<p>Created restore script at $restore_file</p>";
} else {
    echo "<p>Failed to create restore script</p>";
}