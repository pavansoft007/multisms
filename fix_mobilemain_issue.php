<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Fix Mobile Main Issue</h1>";

// Fix the .htaccess file
$htaccess_file = '.htaccess';
$htaccess_content = <<<EOT
<IfModule mod_rewrite.c>
    # Enable URL rewriting
    RewriteEngine On
    
    # Set the base directory for rewrites
    # RewriteBase /multisms/
    
    # Redirect root to mobile_redirect.php
    RewriteCond %{REQUEST_URI} ^/$
    RewriteRule ^$ mobile_redirect.php [L]
    
    # Handle direct access to mobilemain (without .php extension)
    RewriteCond %{REQUEST_URI} ^/multisms/mobilemain$
    RewriteRule ^mobilemain$ mobilemain.php [L]
    
    # Handle direct access to other controllers
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^([a-zA-Z0-9_-]+)$ index.php/$1 [L,QSA]
    
    # Standard CodeIgniter routing - handle segments
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

# Prevent directory listing
Options -Indexes

# Set default character set
AddDefaultCharset UTF-8

# Disable server signature
ServerSignature Off
EOT;

// Create a backup of the original file
$backup_file = '.htaccess_backup_' . date('Y-m-d_H-i-s');
if (file_exists($htaccess_file) && copy($htaccess_file, $backup_file)) {
    echo "<p>Created backup of original .htaccess at $backup_file</p>";
    
    // Write the new content to the file
    if (file_put_contents($htaccess_file, $htaccess_content)) {
        echo "<p>Updated the .htaccess file with simplified routing</p>";
    } else {
        echo "<p>Failed to update the .htaccess file</p>";
    }
} else {
    echo "<p>Failed to create backup of original .htaccess</p>";
}

// Fix the mobilemain file
$mobilemain_file = 'mobilemain';
$mobilemain_content = <<<EOT
<?php
// Redirect to the mobilemain.php file
header('Location: mobilemain.php');
exit;
EOT;

if (file_exists($mobilemain_file)) {
    echo "<p>The mobilemain file already exists. Updating it...</p>";
    if (file_put_contents($mobilemain_file, $mobilemain_content)) {
        echo "<p>Updated mobilemain file to redirect to mobilemain.php</p>";
    } else {
        echo "<p>Failed to update mobilemain file</p>";
    }
} else {
    echo "<p>The mobilemain file does not exist. Creating it...</p>";
    if (file_put_contents($mobilemain_file, $mobilemain_content)) {
        echo "<p>Created mobilemain file that redirects to mobilemain.php</p>";
    } else {
        echo "<p>Failed to create mobilemain file</p>";
    }
}

// Create a web.config file for IIS servers
$web_config_file = 'web.config';
$web_config_content = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <rewrite>
            <rules>
                <rule name="Redirect to mobile_redirect.php">
                    <match url="^$" />
                    <action type="Rewrite" url="mobile_redirect.php" />
                </rule>
                <rule name="Mobilemain Direct">
                    <match url="^mobilemain$" />
                    <action type="Rewrite" url="mobilemain.php" />
                </rule>
                <rule name="CodeIgniter">
                    <match url="^(.*)$" />
                    <conditions>
                        <add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="index.php/{R:1}" appendQueryString="true" />
                </rule>
            </rules>
        </rewrite>
    </system.webServer>
</configuration>
EOT;

if (!file_exists($web_config_file)) {
    if (file_put_contents($web_config_file, $web_config_content)) {
        echo "<p>Created web.config file for IIS servers</p>";
    } else {
        echo "<p>Failed to create web.config file</p>";
    }
} else {
    echo "<p>web.config file already exists</p>";
}

echo "<h2>Solution Summary</h2>";
echo "<p>I've implemented multiple solutions to ensure the mobile cards functionality works correctly:</p>";
echo "<ol>";
echo "<li>Fixed the .htaccess file to properly handle the mobilemain URL</li>";
echo "<li>Updated the mobilemain file to redirect to mobilemain.php</li>";
echo "<li>Created a web.config file for IIS servers</li>";
echo "</ol>";

echo "<h2>How to Access the Mobile Main Page</h2>";
echo "<p>You can now access the mobile main page using any of these URLs:</p>";
echo "<ul>";
echo "<li><a href='mobilemain.php'>mobilemain.php</a> - This is the recommended URL</li>";
echo "<li><a href='mobilemain'>mobilemain</a> - This should now redirect to mobilemain.php</li>";
echo "</ul>";

echo "<h2>Troubleshooting</h2>";
echo "<p>If you still encounter issues, please try the following:</p>";
echo "<ol>";
echo "<li>Check if mod_rewrite is enabled in your Apache configuration</li>";
echo "<li>Make sure AllowOverride is set to All in your Apache configuration</li>";
echo "<li>Try accessing the mobilemain.php file directly</li>";
echo "</ol>";

// Check if mod_rewrite is enabled
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "<p>✅ mod_rewrite is enabled.</p>";
    } else {
        echo "<p>❌ mod_rewrite is not enabled. Please enable it in your Apache configuration.</p>";
    }
} else {
    echo "<p>⚠️ Cannot check if mod_rewrite is enabled. Please check your Apache configuration.</p>";
}

// Check PHP version
echo "<p>PHP Version: " . phpversion() . "</p>";

// Check server software
echo "<p>Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";

// Check document root
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";

// Check script filename
echo "<p>Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "</p>";

// Check request URI
echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";

// Check server name
echo "<p>Server Name: " . $_SERVER['SERVER_NAME'] . "</p>";

// Check server port
echo "<p>Server Port: " . $_SERVER['SERVER_PORT'] . "</p>";

// Check HTTP host
echo "<p>HTTP Host: " . $_SERVER['HTTP_HOST'] . "</p>";

// Check if the server is running on Windows
echo "<p>Server OS: " . (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'Windows' : 'Not Windows') . "</p>";

// Check if the server is running on XAMPP
echo "<p>XAMPP: " . (strpos($_SERVER['DOCUMENT_ROOT'], 'xampp') !== false ? 'Yes' : 'No') . "</p>";

// Check if the server is running on localhost
echo "<p>Localhost: " . (($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') ? 'Yes' : 'No') . "</p>";