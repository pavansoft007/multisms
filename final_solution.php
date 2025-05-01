<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Final Solution for Mobile Cards Issue</h1>";

// Create a direct file for the mobilemain URL
$mobilemain_file = 'mobilemain';
$mobilemain_content = <<<EOT
<?php
// Redirect to the standalone version that we know works
header('Location: standalone_mobilemain.php');
exit;
EOT;

if (file_exists($mobilemain_file)) {
    echo "<p>The mobilemain file already exists.</p>";
} else {
    if (file_put_contents($mobilemain_file, $mobilemain_content)) {
        echo "<p>Created mobilemain file that redirects to the standalone version</p>";
    } else {
        echo "<p>Failed to create mobilemain file</p>";
    }
}

// Create a .htaccess file that handles the mobilemain URL
$htaccess_file = '.htaccess';
$htaccess_content = <<<EOT
<IfModule mod_rewrite.c>
    # Enable URL rewriting
    RewriteEngine On
    
    # Redirect root to mobile_redirect.php
    RewriteCond %{REQUEST_URI} ^/$
    RewriteRule ^$ mobile_redirect.php [L]
    
    # Handle direct access to mobilemain (without .php extension)
    RewriteCond %{REQUEST_URI} ^/multisms/mobilemain$
    RewriteRule ^mobilemain$ standalone_mobilemain.php [L]
    
    # Standard CodeIgniter routing
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L,QSA]
</IfModule>
EOT;

// Create a backup of the original file
$backup_file = '.htaccess_backup_final_' . date('Y-m-d_H-i-s');
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
                    <action type="Rewrite" url="standalone_mobilemain.php" />
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
echo "<li>Created a direct file at <code>mobilemain</code> that redirects to the standalone version</li>";
echo "<li>Updated the .htaccess file with simplified routing rules</li>";
echo "<li>Added a web.config file for IIS servers</li>";
echo "</ol>";

echo "<h2>How to Access the Mobile Main Page</h2>";
echo "<p>You can now access the mobile main page using any of these URLs:</p>";
echo "<ul>";
echo "<li><a href='mobilemain'>mobilemain</a> - This will redirect to the standalone version</li>";
echo "<li><a href='standalone_mobilemain.php'>standalone_mobilemain.php</a> - The standalone version that works</li>";
echo "<li><a href='mobilemain_direct.php'>mobilemain_direct.php</a> - Another direct implementation</li>";
echo "</ul>";

echo "<p>If you still encounter issues, please try the following:</p>";
echo "<ol>";
echo "<li>Check if mod_rewrite is enabled in your Apache configuration</li>";
echo "<li>Make sure AllowOverride is set to All in your Apache configuration</li>";
echo "<li>Try accessing the standalone version directly</li>";
echo "</ol>";

echo "<p>You can also use the <a href='direct_access.php'>diagnostic tool</a> to troubleshoot any remaining issues.</p>";