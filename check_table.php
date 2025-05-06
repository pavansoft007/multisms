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
    echo "Table theme_settings exists";
    
    // Show table data
    $sql = "SELECT * FROM theme_settings";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<br>Table data:<br>";
        while($row = $result->fetch_assoc()) {
            echo "id: " . $row["id"]. " - branch_id: " . $row["branch_id"]. " - dark_skin: " . $row["dark_skin"]. "<br>";
        }
    } else {
        echo "<br>No data in table";
    }
} else {
    echo "Table theme_settings does not exist";
}

$conn->close();
?>