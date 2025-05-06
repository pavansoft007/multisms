<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'multisms';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create table
$sql = "CREATE TABLE IF NOT EXISTS theme_settings (
    id int(11) NOT NULL AUTO_INCREMENT,
    branch_id int(11) NOT NULL DEFAULT 0,
    dark_skin enum('true','false') NOT NULL DEFAULT 'false',
    border_mode enum('true','false') NOT NULL DEFAULT 'true',
    sidebar_color varchar(20) NOT NULL DEFAULT 'default',
    sidebar_text_color varchar(20) NOT NULL DEFAULT 'light',
    menu_text_color varchar(20) NOT NULL DEFAULT 'light',
    menu_bg_color varchar(20) NOT NULL DEFAULT 'default',
    active_menu_text_color varchar(20) NOT NULL DEFAULT 'light',
    active_menu_bg varchar(20) NOT NULL DEFAULT 'default',
    menu_hover_style varchar(20) NOT NULL DEFAULT 'default',
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($conn->query($sql) === TRUE) {
    echo "Table theme_settings created successfully";
    
    // Insert default values
    $sql = "INSERT INTO theme_settings (branch_id, dark_skin, border_mode, sidebar_color, sidebar_text_color, menu_text_color, menu_bg_color, active_menu_text_color, active_menu_bg, menu_hover_style)
    SELECT 0, 'false', 'true', 'default', 'light', 'light', 'default', 'light', 'default', 'default'
    FROM dual
    WHERE NOT EXISTS (SELECT * FROM theme_settings WHERE branch_id = 0)";
    
    if ($conn->query($sql) === TRUE) {
        echo "<br>Default values inserted successfully";
    } else {
        echo "<br>Error inserting default values: " . $conn->error;
    }
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>