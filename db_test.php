<?php
// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'multisms';

// Try to connect to the database
try {
    $conn = new mysqli($hostname, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    echo "Database Connection Successful!<br>";
    
    // Check if theme_settings table exists
    $result = $conn->query("SHOW TABLES LIKE 'theme_settings'");
    if ($result->num_rows > 0) {
        echo "theme_settings table exists.<br>";
        
        // Check theme_settings table structure
        $result = $conn->query("DESCRIBE theme_settings");
        echo "<h3>theme_settings Table Structure:</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "theme_settings table does not exist!<br>";
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>