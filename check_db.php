<?php
// Simple script to check the database
echo "Checking database...\n";

// Try to get database name from config
$config_file = file_get_contents('application/config/database.php');
preg_match("/['|\"]database['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
$db_name = isset($matches[3]) ? $matches[3] : 'multisms';

echo "Trying to connect to database: $db_name\n";

try {
    $db = new PDO("mysql:host=localhost;dbname=$db_name", 'root', '');
    echo "Database connected successfully\n";
    
    // Check if table exists
    $stmt = $db->query("SHOW TABLES LIKE 'mobile_cards_config'");
    echo "Table exists: " . ($stmt->rowCount() > 0 ? 'Yes' : 'No') . "\n";
    
    if ($stmt->rowCount() > 0) {
        // Get all records
        $stmt = $db->query("SELECT * FROM mobile_cards_config");
        echo "Records found: " . $stmt->rowCount() . "\n";
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "ID: {$row['id']}, Role ID: {$row['role_id']}, Card Item: {$row['card_item']}, Order: {$row['card_order']}, Status: {$row['status']}\n";
        }
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}