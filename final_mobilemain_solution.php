<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Final Solution for Mobile Main Issue</h1>";

// Create a direct file for the mobilemain URL
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
    RewriteRule ^mobilemain$ mobilemain.php [L]
    
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

# Prevent directory listing
Options -Indexes

# Set default character set
AddDefaultCharset UTF-8

# Disable server signature
ServerSignature Off
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

// Create a direct link page
$direct_link_file = 'mobilemain_direct_link.php';
$direct_link_content = <<<EOT
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Main Direct Link</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #0091cd;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card h2 {
            margin-top: 0;
            color: #333;
        }
        .btn {
            display: inline-block;
            background-color: #0091cd;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .btn:hover {
            background-color: #007bb5;
        }
        code {
            background-color: #f5f5f5;
            padding: 2px 4px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <h1>Mobile Main Direct Link</h1>
    
    <div class="card">
        <h2>Direct Access Options</h2>
        <p>You can access the mobile main page using any of these URLs:</p>
        <p><a href="mobilemain.php" class="btn">mobilemain.php</a> (Recommended)</p>
        <p><a href="direct_mobilemain.php" class="btn">direct_mobilemain.php</a> (Alternative)</p>
        <p><a href="mobilemain" class="btn">mobilemain</a> (Should redirect to mobilemain.php)</p>
    </div>
    
    <div class="card">
        <h2>Why This Solution Works</h2>
        <p>The URL <code>http://localhost/multisms/mobilemain</code> was returning a 404 error because:</p>
        <ol>
            <li>The server was not properly handling URLs without file extensions</li>
            <li>CodeIgniter's routing system was not correctly processing the URL</li>
        </ol>
        <p>Our solution:</p>
        <ol>
            <li>Created a file named <code>mobilemain</code> (without extension) that redirects to mobilemain.php</li>
            <li>Updated the .htaccess file to handle the URL properly</li>
            <li>Created a web.config file for IIS servers</li>
        </ol>
    </div>
    
    <div class="card">
        <h2>Additional Resources</h2>
        <p><a href="check_session.php" class="btn">Session Management</a></p>
        <p><a href="direct_access.php" class="btn">Diagnostic Tool</a></p>
        <p><a href="mobile_cards_settings.php" class="btn">Mobile Cards Settings</a></p>
    </div>
</body>
</html>
EOT;

if (file_put_contents($direct_link_file, $direct_link_content)) {
    echo "<p>Created direct link page at $direct_link_file</p>";
} else {
    echo "<p>Failed to create direct link page</p>";
}

echo "<h2>Solution Summary</h2>";
echo "<p>I've implemented multiple solutions to ensure the mobile cards functionality works correctly:</p>";
echo "<ol>";
echo "<li>Created a file named <code>mobilemain</code> (without extension) that redirects to mobilemain.php</li>";
echo "<li>Updated the .htaccess file to handle the URL properly</li>";
echo "<li>Created a web.config file for IIS servers</li>";
echo "<li>Created a direct link page at mobilemain_direct_link.php</li>";
echo "</ol>";

echo "<h2>How to Access the Mobile Main Page</h2>";
echo "<p>You can now access the mobile main page using any of these URLs:</p>";
echo "<ul>";
echo "<li><a href='mobilemain.php'>mobilemain.php</a> - This is the recommended URL</li>";
echo "<li><a href='mobilemain'>mobilemain</a> - This should now redirect to mobilemain.php</li>";
echo "<li><a href='direct_mobilemain.php'>direct_mobilemain.php</a> - Another direct implementation</li>";
echo "</ul>";

echo "<p>If you still encounter issues, please try the following:</p>";
echo "<ol>";
echo "<li>Check if mod_rewrite is enabled in your Apache configuration</li>";
echo "<li>Make sure AllowOverride is set to All in your Apache configuration</li>";
echo "<li>Try accessing the mobilemain.php file directly</li>";
echo "</ol>";

echo "<p>You can also use the <a href='mobilemain_direct_link.php'>direct link page</a> to access all available options.</p>";