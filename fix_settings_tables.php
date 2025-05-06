<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define BASEPATH to bypass CodeIgniter security
define('BASEPATH', true);

// Database connection parameters
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'multisms';

try {
    // Connect to the database
    $db = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully<br>";
    
    // Check if theme_settings table exists
    $stmt = $db->query('SHOW TABLES LIKE "theme_settings"');
    if($stmt->rowCount() == 0) {
        echo "Creating theme_settings table...<br>";
        
        // Create the theme_settings table
        $sql = "CREATE TABLE `theme_settings` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `branch_id` int(11) NOT NULL,
            `dark_skin` varchar(10) NOT NULL DEFAULT 'false',
            `border_mode` varchar(10) NOT NULL DEFAULT 'true',
            `sidebar_color` varchar(20) NOT NULL DEFAULT 'default',
            `sidebar_text_color` varchar(20) NOT NULL DEFAULT 'light',
            `menu_text_color` varchar(20) NOT NULL DEFAULT 'light',
            `menu_bg_color` varchar(20) NOT NULL DEFAULT 'default',
            `active_menu_text_color` varchar(20) NOT NULL DEFAULT 'light',
            `active_menu_bg` varchar(20) NOT NULL DEFAULT 'default',
            `menu_hover_style` varchar(20) NOT NULL DEFAULT 'default',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        $db->exec($sql);
        echo "theme_settings table created successfully<br>";
    } else {
        echo "theme_settings table already exists<br>";
    }
    
    // Check if there's a record for branch_id 0 in theme_settings
    $stmt = $db->query('SELECT * FROM theme_settings WHERE branch_id = 0');
    if($stmt->rowCount() == 0) {
        // Insert default record for branch_id 0
        $sql = "INSERT INTO `theme_settings` 
                (`branch_id`, `dark_skin`, `border_mode`, `sidebar_color`, `sidebar_text_color`, 
                `menu_text_color`, `menu_bg_color`, `active_menu_text_color`, `active_menu_bg`, `menu_hover_style`) 
                VALUES 
                (0, 'false', 'true', 'default', 'light', 'light', 'default', 'light', 'default', 'default')";
        
        $db->exec($sql);
        echo "Default theme settings inserted for branch_id 0<br>";
    } else {
        echo "Default theme settings already exist for branch_id 0<br>";
    }
    
    // Check if global_settings table exists
    $stmt = $db->query('SHOW TABLES LIKE "global_settings"');
    if($stmt->rowCount() == 0) {
        echo "Creating global_settings table...<br>";
        
        // Create the global_settings table
        $sql = "CREATE TABLE `global_settings` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `branch_id` int(11) NOT NULL,
            `institute_name` varchar(255) NOT NULL,
            `institution_code` varchar(255) NOT NULL,
            `reg_prefix` varchar(255) NOT NULL,
            `address` text NOT NULL,
            `mobileno` varchar(255) NOT NULL,
            `currency` varchar(255) NOT NULL,
            `currency_symbol` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL,
            `translation` varchar(255) NOT NULL,
            `footer_text` varchar(255) NOT NULL,
            `timezone` varchar(255) NOT NULL,
            `animations` varchar(255) NOT NULL DEFAULT 'fadeIn',
            `date_format` varchar(255) NOT NULL,
            `facebook_url` varchar(255) NOT NULL,
            `twitter_url` varchar(255) NOT NULL,
            `linkedin_url` varchar(255) NOT NULL,
            `youtube_url` varchar(255) NOT NULL,
            `session_id` int(11) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        $db->exec($sql);
        echo "global_settings table created successfully<br>";
    } else {
        echo "global_settings table already exists<br>";
    }
    
    // Check if there's a record for branch_id 0 in global_settings
    $stmt = $db->query('SELECT * FROM global_settings WHERE branch_id = 0');
    if($stmt->rowCount() == 0) {
        // Insert default record for branch_id 0
        $sql = "INSERT INTO `global_settings` 
                (`branch_id`, `institute_name`, `institution_code`, `reg_prefix`, `address`, 
                `mobileno`, `currency`, `currency_symbol`, `email`, `translation`, 
                `footer_text`, `timezone`, `animations`, `date_format`, `facebook_url`, 
                `twitter_url`, `linkedin_url`, `youtube_url`, `session_id`) 
                VALUES 
                (0, 'MultiSMS', 'MS', 'on', '123 Main Street', 
                '1234567890', 'INR', '₹', 'info@example.com', 'english', 
                'MultiSMS © 2023', 'Asia/Kolkata', 'fadeIn', 'Y-m-d', 'https://facebook.com', 
                'https://twitter.com', 'https://linkedin.com', 'https://youtube.com', 1)";
        
        $db->exec($sql);
        echo "Default global settings inserted for branch_id 0<br>";
    } else {
        echo "Default global settings already exist for branch_id 0<br>";
    }
    
    // Check if global_images table exists
    $stmt = $db->query('SHOW TABLES LIKE "global_images"');
    if($stmt->rowCount() == 0) {
        echo "Creating global_images table...<br>";
        
        // Create the global_images table
        $sql = "CREATE TABLE `global_images` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `branch_id` int(11) NOT NULL,
            `system_logo` varchar(255) NOT NULL DEFAULT 'logo.png',
            `text_logo` varchar(255) NOT NULL DEFAULT 'logo-small.png',
            `printing_logo` varchar(255) NOT NULL DEFAULT 'printing-logo.png',
            `report_logo` varchar(255) NOT NULL DEFAULT 'report-card-logo.png',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        $db->exec($sql);
        echo "global_images table created successfully<br>";
        
        // Insert default record
        $sql = "INSERT INTO `global_images` 
                (`id`, `branch_id`, `system_logo`, `text_logo`, `printing_logo`, `report_logo`) 
                VALUES 
                (1, 0, 'logo.png', 'logo-small.png', 'printing-logo.png', 'report-card-logo.png')";
        
        $db->exec($sql);
        echo "Default global images inserted<br>";
    } else {
        echo "global_images table already exists<br>";
        
        // Check if there's a record with id 1
        $stmt = $db->query('SELECT * FROM global_images WHERE id = 1');
        if($stmt->rowCount() == 0) {
            // Insert default record
            $sql = "INSERT INTO `global_images` 
                    (`id`, `branch_id`, `system_logo`, `text_logo`, `printing_logo`, `report_logo`) 
                    VALUES 
                    (1, 0, 'logo.png', 'logo-small.png', 'printing-logo.png', 'report-card-logo.png')";
            
            $db->exec($sql);
            echo "Default global images inserted<br>";
        } else {
            echo "Default global images already exist<br>";
        }
    }
    
    echo "<br>All fixes have been applied successfully. You can now try accessing the sidebar and universal settings pages.";
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>