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

// Check if table exists
$sql = "SHOW TABLES LIKE 'theme_settings'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Table theme_settings exists<br>";
    
    // Show table structure
    $sql = "DESCRIBE theme_settings";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "Table structure:<br>";
        echo "<table border='1'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Field"] . "</td>";
            echo "<td>" . $row["Type"] . "</td>";
            echo "<td>" . $row["Null"] . "</td>";
            echo "<td>" . $row["Key"] . "</td>";
            echo "<td>" . $row["Default"] . "</td>";
            echo "<td>" . $row["Extra"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Error getting table structure";
    }
    
    // Show table data
    $sql = "SELECT * FROM theme_settings";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<br>Table data:<br>";
        echo "<table border='1'>";
        echo "<tr><th>id</th><th>branch_id</th><th>dark_skin</th><th>border_mode</th><th>sidebar_color</th><th>sidebar_text_color</th><th>menu_text_color</th><th>menu_bg_color</th><th>active_menu_text_color</th><th>active_menu_bg</th><th>menu_hover_style</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["branch_id"] . "</td>";
            echo "<td>" . $row["dark_skin"] . "</td>";
            echo "<td>" . $row["border_mode"] . "</td>";
            echo "<td>" . $row["sidebar_color"] . "</td>";
            echo "<td>" . $row["sidebar_text_color"] . "</td>";
            echo "<td>" . $row["menu_text_color"] . "</td>";
            echo "<td>" . $row["menu_bg_color"] . "</td>";
            echo "<td>" . $row["active_menu_text_color"] . "</td>";
            echo "<td>" . $row["active_menu_bg"] . "</td>";
            echo "<td>" . $row["menu_hover_style"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<br>No data in table";
    }
} else {
    echo "Table theme_settings does not exist";
}

$conn->close();
?>