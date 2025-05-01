<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Replace Mobilemain Controller</h1>";

// Backup the old controller
$old_file = 'application/controllers/Mobilemain.php';
$backup_file = 'application/controllers/Mobilemain_backup.php';
$new_file = 'application/controllers/Mobilemain_new.php';

if (file_exists($old_file)) {
    // Create backup
    if (copy($old_file, $backup_file)) {
        echo "<p>Created backup of old controller at $backup_file</p>";
    } else {
        echo "<p>Failed to create backup of old controller</p>";
        exit;
    }
    
    // Read the new controller
    $new_content = file_get_contents($new_file);
    
    // Replace the class name
    $new_content = str_replace('class Mobilemain_new', 'class Mobilemain', $new_content);
    
    // Write to the old file
    if (file_put_contents($old_file, $new_content)) {
        echo "<p>Successfully replaced the Mobilemain controller</p>";
    } else {
        echo "<p>Failed to replace the Mobilemain controller</p>";
        exit;
    }
    
    echo "<p>The Mobilemain controller has been replaced with the new version.</p>";
    echo "<p>You can now <a href='index.php/mobilemain?force_mobile=1'>test the mobilemain page</a>.</p>";
    echo "<p>If you encounter any issues, you can restore the backup by running the restore_controller.php script.</p>";
    
    // Create a restore script
    $restore_script = <<<EOT
<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Restore Mobilemain Controller</h1>";

// Restore the old controller
\$old_file = 'application/controllers/Mobilemain.php';
\$backup_file = 'application/controllers/Mobilemain_backup.php';

if (file_exists(\$backup_file)) {
    // Restore from backup
    if (copy(\$backup_file, \$old_file)) {
        echo "<p>Successfully restored the Mobilemain controller from backup</p>";
    } else {
        echo "<p>Failed to restore the Mobilemain controller from backup</p>";
        exit;
    }
    
    echo "<p>The Mobilemain controller has been restored to its original version.</p>";
    echo "<p>You can now <a href='index.php/mobilemain?force_mobile=1'>test the mobilemain page</a>.</p>";
} else {
    echo "<p>Backup file not found at \$backup_file</p>";
}
EOT;
    
    file_put_contents('restore_controller.php', $restore_script);
    echo "<p>Created restore_controller.php script</p>";
} else {
    echo "<p>Mobilemain controller not found at $old_file</p>";
}