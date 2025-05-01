<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Fix URL Routing</h1>";

// Check if the .htaccess file exists
$htaccess_file = '.htaccess';
if (!file_exists($htaccess_file)) {
    echo "<p>Error: .htaccess file not found</p>";
    exit;
}

// Create a backup of the original file
$backup_file = '.htaccess_backup_' . date('Y-m-d_H-i-s');
if (copy($htaccess_file, $backup_file)) {
    echo "<p>Created backup of original .htaccess at $backup_file</p>";
} else {
    echo "<p>Failed to create backup of original .htaccess</p>";
    exit;
}

// Read the original file
$original_content = file_get_contents($htaccess_file);

// Create a new .htaccess file with improved routing
$new_content = <<<EOT
<IfModule mod_rewrite.c>
RewriteEngine On

# Check if the request is for the root directory
RewriteCond %{REQUEST_URI} ^/$
# Redirect to mobile_redirect.php
RewriteRule ^$ mobile_redirect.php [L]

# Redirect direct controller access to use index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^mobilemain$ index.php/mobilemain?force_mobile=1 [L,R=302]

# Standard CodeIgniter routing
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L,QSA]
</IfModule>

# PHP settings
<IfModule mod_php7.c>
php_value display_errors 1
php_value display_startup_errors 1
php_value error_reporting E_ALL
</IfModule>

<IfModule mod_php8.c>
php_value display_errors 1
php_value display_startup_errors 1
php_value error_reporting E_ALL
</IfModule>
EOT;

// Write the new content to the file
if (file_put_contents($htaccess_file, $new_content)) {
    echo "<p>Successfully updated the .htaccess file</p>";
} else {
    echo "<p>Failed to update the .htaccess file</p>";
    exit;
}

echo "<p>The .htaccess file has been updated with improved routing rules.</p>";
echo "<p>You can now try accessing the mobile main page using any of these URLs:</p>";
echo "<ul>";
echo "<li><a href='mobilemain'>Mobile Main (without index.php)</a></li>";
echo "<li><a href='index.php/mobilemain?force_mobile=1'>Mobile Main (with index.php)</a></li>";
echo "</ul>";
echo "<p>If you encounter any issues, you can restore the backup by copying $backup_file back to $htaccess_file.</p>";

// Create a restore script
$restore_script = <<<EOT
<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Restore .htaccess</h1>";

// Restore the original .htaccess
\$htaccess_file = '.htaccess';
\$backup_file = '$backup_file';

if (file_exists(\$backup_file)) {
    // Restore from backup
    if (copy(\$backup_file, \$htaccess_file)) {
        echo "<p>Successfully restored the .htaccess from backup</p>";
    } else {
        echo "<p>Failed to restore the .htaccess from backup</p>";
        exit;
    }
    
    echo "<p>The .htaccess file has been restored to its original version.</p>";
} else {
    echo "<p>Backup file not found at \$backup_file</p>";
}
EOT;

$restore_file = 'restore_htaccess.php';
if (file_put_contents($restore_file, $restore_script)) {
    echo "<p>Created restore script at $restore_file</p>";
} else {
    echo "<p>Failed to create restore script</p>";
}