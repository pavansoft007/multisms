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
    if($stmt->rowCount() > 0) {
        echo "theme_settings table exists, checking columns...<br>";
        
        // Get the current columns
        $stmt = $db->query('SHOW COLUMNS FROM theme_settings');
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "Current columns: " . implode(", ", $columns) . "<br>";
        
        // Check and add missing columns
        $requiredColumns = [
            'sidebar_text_color' => "ALTER TABLE `theme_settings` ADD COLUMN `sidebar_text_color` varchar(20) NOT NULL DEFAULT 'light'",
            'menu_text_color' => "ALTER TABLE `theme_settings` ADD COLUMN `menu_text_color` varchar(20) NOT NULL DEFAULT 'light'",
            'menu_bg_color' => "ALTER TABLE `theme_settings` ADD COLUMN `menu_bg_color` varchar(20) NOT NULL DEFAULT 'default'",
            'active_menu_text_color' => "ALTER TABLE `theme_settings` ADD COLUMN `active_menu_text_color` varchar(20) NOT NULL DEFAULT 'light'",
            'active_menu_bg' => "ALTER TABLE `theme_settings` ADD COLUMN `active_menu_bg` varchar(20) NOT NULL DEFAULT 'default'",
            'menu_hover_style' => "ALTER TABLE `theme_settings` ADD COLUMN `menu_hover_style` varchar(20) NOT NULL DEFAULT 'default'"
        ];
        
        foreach($requiredColumns as $column => $sql) {
            if(!in_array($column, $columns)) {
                echo "Adding missing column: $column<br>";
                $db->exec($sql);
            } else {
                echo "Column $column already exists<br>";
            }
        }
        
        echo "theme_settings table structure has been updated<br>";
        
        // Update the default record for branch_id 0 if it exists
        $stmt = $db->query('SELECT * FROM theme_settings WHERE branch_id = 0 LIMIT 1');
        if($stmt->rowCount() > 0) {
            echo "Updating default record for branch_id 0<br>";
            
            $sql = "UPDATE `theme_settings` SET 
                    `sidebar_text_color` = 'light',
                    `menu_text_color` = 'light',
                    `menu_bg_color` = 'default',
                    `active_menu_text_color` = 'light',
                    `active_menu_bg` = 'default',
                    `menu_hover_style` = 'default'
                    WHERE `branch_id` = 0";
            
            $db->exec($sql);
            echo "Default record updated<br>";
        }
        
    } else {
        echo "theme_settings table does NOT exist, creating it...<br>";
        
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
        
        // Insert default record for branch_id 0
        $sql = "INSERT INTO `theme_settings` 
                (`branch_id`, `dark_skin`, `border_mode`, `sidebar_color`, `sidebar_text_color`, 
                `menu_text_color`, `menu_bg_color`, `active_menu_text_color`, `active_menu_bg`, `menu_hover_style`) 
                VALUES 
                (0, 'false', 'true', 'default', 'light', 'light', 'default', 'light', 'default', 'default')";
        
        $db->exec($sql);
        echo "Default theme settings inserted for branch_id 0<br>";
    }
    
    echo "<br>All fixes have been applied successfully. You can now try accessing the sidebar and universal settings pages.";
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>