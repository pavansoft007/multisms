<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Set up a test session
$_SESSION['loggedin'] = true;
$_SESSION['role_id'] = 1; // Admin role
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'admin';
$_SESSION['name'] = 'Administrator';

echo "<h1>Test Mobilemain Controller</h1>";

// Try to get database name from config
$config_file = file_get_contents('application/config/database.php');
preg_match("/['|\"]database['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
$db_name = isset($matches[3]) ? $matches[3] : 'multisms';

// Get database credentials
preg_match("/['|\"]username['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
$db_user = isset($matches[3]) ? $matches[3] : 'root';

preg_match("/['|\"]password['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
$db_pass = isset($matches[3]) ? $matches[3] : '';

try {
    // Connect to database
    $db = new PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>Database connected successfully</p>";
    
    // Check if the mobile_cards_config table exists
    $stmt = $db->query("SHOW TABLES LIKE 'mobile_cards_config'");
    $table_exists = ($stmt->rowCount() > 0);
    
    echo "<p>Table mobile_cards_config exists: " . ($table_exists ? 'Yes' : 'No') . "</p>";
    
    if ($table_exists) {
        // Get the current role ID
        $role_id = isset($_SESSION['role_id']) ? (int)$_SESSION['role_id'] : 1;
        
        echo "<p>Current role ID: $role_id</p>";
        
        // Get enabled cards for this role
        $stmt = $db->prepare("SELECT card_item FROM mobile_cards_config WHERE role_id = ? AND status = 1 ORDER BY card_order ASC");
        $stmt->execute([$role_id]);
        
        echo "<h2>Enabled Cards from Database</h2>";
        
        if ($stmt->rowCount() > 0) {
            echo "<ul>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<li>{$row['card_item']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No enabled cards found for role $role_id</p>";
        }
        
        // Now let's check the Mobilemain controller file
        $controller_file = 'application/controllers/Mobilemain.php';
        
        if (file_exists($controller_file)) {
            echo "<h2>Mobilemain Controller Analysis</h2>";
            
            $controller_content = file_get_contents($controller_file);
            
            // Check if the controller is using the mobile_cards_config table
            if (strpos($controller_content, 'mobile_cards_config') !== false) {
                echo "<p>Controller is using the mobile_cards_config table</p>";
            } else {
                echo "<p>Controller is NOT using the mobile_cards_config table</p>";
            }
            
            // Check if the controller has hardcoded cards
            if (preg_match('/\$cards\s*=\s*array\s*\(/i', $controller_content)) {
                echo "<p>Controller has hardcoded cards array</p>";
                
                // Extract the hardcoded cards array
                preg_match_all('/\$cards\s*=\s*array\s*\(.*?\);/s', $controller_content, $matches);
                
                if (!empty($matches[0])) {
                    echo "<pre>";
                    foreach ($matches[0] as $match) {
                        echo htmlspecialchars($match) . "\n\n";
                    }
                    echo "</pre>";
                }
            } else {
                echo "<p>Controller does not have hardcoded cards array</p>";
            }
            
            // Check if the controller is using the get_mobile_cards function
            if (strpos($controller_content, 'get_mobile_cards') !== false) {
                echo "<p>Controller is using the get_mobile_cards function</p>";
            } else {
                echo "<p>Controller is NOT using the get_mobile_cards function</p>";
            }
            
            // Check if the controller is using the get_default_cards function
            if (strpos($controller_content, 'get_default_cards') !== false) {
                echo "<p>Controller is using the get_default_cards function</p>";
            } else {
                echo "<p>Controller is NOT using the get_default_cards function</p>";
            }
            
            // Check if the controller is using the get_card_details function
            if (strpos($controller_content, 'get_card_details') !== false) {
                echo "<p>Controller is using the get_card_details function</p>";
            } else {
                echo "<p>Controller is NOT using the get_card_details function</p>";
            }
            
            // Check if the controller has a private get_default_cards method
            if (preg_match('/private\s+function\s+get_default_cards/i', $controller_content)) {
                echo "<p>Controller has a private get_default_cards method</p>";
            } else {
                echo "<p>Controller does NOT have a private get_default_cards method</p>";
            }
            
            // Check if the controller has a private get_card_details method
            if (preg_match('/private\s+function\s+get_card_details/i', $controller_content)) {
                echo "<p>Controller has a private get_card_details method</p>";
            } else {
                echo "<p>Controller does NOT have a private get_card_details method</p>";
            }
            
            // Check the index method
            if (preg_match('/public\s+function\s+index.*?{.*?}/s', $controller_content, $matches)) {
                echo "<h3>Index Method Analysis</h3>";
                
                $index_method = $matches[0];
                
                // Check if the index method is using the mobile_cards_config table
                if (strpos($index_method, 'mobile_cards_config') !== false) {
                    echo "<p>Index method is using the mobile_cards_config table</p>";
                } else {
                    echo "<p>Index method is NOT using the mobile_cards_config table</p>";
                }
                
                // Check if the index method is using the get_mobile_cards function
                if (strpos($index_method, 'get_mobile_cards') !== false) {
                    echo "<p>Index method is using the get_mobile_cards function</p>";
                } else {
                    echo "<p>Index method is NOT using the get_mobile_cards function</p>";
                }
                
                // Check if the index method is using the get_default_cards function
                if (strpos($index_method, 'get_default_cards') !== false) {
                    echo "<p>Index method is using the get_default_cards function</p>";
                } else {
                    echo "<p>Index method is NOT using the get_default_cards function</p>";
                }
                
                // Check if the index method is using the get_card_details function
                if (strpos($index_method, 'get_card_details') !== false) {
                    echo "<p>Index method is using the get_card_details function</p>";
                } else {
                    echo "<p>Index method is NOT using the get_card_details function</p>";
                }
            } else {
                echo "<p>Could not find the index method in the controller</p>";
            }
        } else {
            echo "<p>Mobilemain controller file not found</p>";
        }
        
        // Check the mobile_cards_helper.php file
        $helper_file = 'application/helpers/mobile_cards_helper.php';
        
        if (file_exists($helper_file)) {
            echo "<h2>Mobile Cards Helper Analysis</h2>";
            
            $helper_content = file_get_contents($helper_file);
            
            // Check if the helper has the get_mobile_cards function
            if (preg_match('/function\s+get_mobile_cards/i', $helper_content)) {
                echo "<p>Helper has the get_mobile_cards function</p>";
            } else {
                echo "<p>Helper does NOT have the get_mobile_cards function</p>";
            }
            
            // Check if the helper has the get_default_cards function
            if (preg_match('/function\s+get_default_cards/i', $helper_content)) {
                echo "<p>Helper has the get_default_cards function</p>";
            } else {
                echo "<p>Helper does NOT have the get_default_cards function</p>";
            }
            
            // Check if the helper has the get_card_details function
            if (preg_match('/function\s+get_card_details/i', $helper_content)) {
                echo "<p>Helper has the get_card_details function</p>";
            } else {
                echo "<p>Helper does NOT have the get_card_details function</p>";
            }
        } else {
            echo "<p>Mobile cards helper file not found</p>";
        }
    }
    
    // Link to direct tools
    echo "<h2>Links</h2>";
    echo "<ul>";
    echo "<li><a href='direct_tools.php'>Direct Tools</a></li>";
    echo "<li><a href='direct_mobilemain.php'>Direct Mobile Main</a></li>";
    echo "<li><a href='direct_settings.php'>Direct Settings</a></li>";
    echo "<li><a href='index.php/mobilemain?force_mobile=1'>Original Mobile Main</a></li>";
    echo "</ul>";
    
} catch(PDOException $e) {
    echo "<p>Database Error: " . $e->getMessage() . "</p>";
}<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Set up a test session
$_SESSION['loggedin'] = true;
$_SESSION['role_id'] = 1; // Admin role
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'admin';
$_SESSION['name'] = 'Administrator';

echo "<h1>Test Mobilemain Controller</h1>";

// Try to get database name from config
$config_file = file_get_contents('application/config/database.php');
preg_match("/['|\"]database['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
$db_name = isset($matches[3]) ? $matches[3] : 'multisms';

// Get database credentials
preg_match("/['|\"]username['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
$db_user = isset($matches[3]) ? $matches[3] : 'root';

preg_match("/['|\"]password['|\"](\s*?)=>(\s*?)['|\"](.*?)['|\"]/", $config_file, $matches);
$db_pass = isset($matches[3]) ? $matches[3] : '';

try {
    // Connect to database
    $db = new PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>Database connected successfully</p>";
    
    // Check if the mobile_cards_config table exists
    $stmt = $db->query("SHOW TABLES LIKE 'mobile_cards_config'");
    $table_exists = ($stmt->rowCount() > 0);
    
    echo "<p>Table mobile_cards_config exists: " . ($table_exists ? 'Yes' : 'No') . "</p>";
    
    if ($table_exists) {
        // Get the current role ID
        $role_id = isset($_SESSION['role_id']) ? (int)$_SESSION['role_id'] : 1;
        
        echo "<p>Current role ID: $role_id</p>";
        
        // Get enabled cards for this role
        $stmt = $db->prepare("SELECT card_item FROM mobile_cards_config WHERE role_id = ? AND status = 1 ORDER BY card_order ASC");
        $stmt->execute([$role_id]);
        
        echo "<h2>Enabled Cards from Database</h2>";
        
        if ($stmt->rowCount() > 0) {
            echo "<ul>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<li>{$row['card_item']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No enabled cards found for role $role_id</p>";
        }
        
        // Now let's check the Mobilemain controller file
        $controller_file = 'application/controllers/Mobilemain.php';
        
        if (file_exists($controller_file)) {
            echo "<h2>Mobilemain Controller Analysis</h2>";
            
            $controller_content = file_get_contents($controller_file);
            
            // Check if the controller is using the mobile_cards_config table
            if (strpos($controller_content, 'mobile_cards_config') !== false) {
                echo "<p>Controller is using the mobile_cards_config table</p>";
            } else {
                echo "<p>Controller is NOT using the mobile_cards_config table</p>";
            }
            
            // Check if the controller has hardcoded cards
            if (preg_match('/\$cards\s*=\s*array\s*\(/i', $controller_content)) {
                echo "<p>Controller has hardcoded cards array</p>";
                
                // Extract the hardcoded cards array
                preg_match_all('/\$cards\s*=\s*array\s*\(.*?\);/s', $controller_content, $matches);
                
                if (!empty($matches[0])) {
                    echo "<pre>";
                    foreach ($matches[0] as $match) {
                        echo htmlspecialchars($match) . "\n\n";
                    }
                    echo "</pre>";
                }
            } else {
                echo "<p>Controller does not have hardcoded cards array</p>";
            }
            
            // Check if the controller is using the get_mobile_cards function
            if (strpos($controller_content, 'get_mobile_cards') !== false) {
                echo "<p>Controller is using the get_mobile_cards function</p>";
            } else {
                echo "<p>Controller is NOT using the get_mobile_cards function</p>";
            }
            
            // Check if the controller is using the get_default_cards function
            if (strpos($controller_content, 'get_default_cards') !== false) {
                echo "<p>Controller is using the get_default_cards function</p>";
            } else {
                echo "<p>Controller is NOT using the get_default_cards function</p>";
            }
            
            // Check if the controller is using the get_card_details function
            if (strpos($controller_content, 'get_card_details') !== false) {
                echo "<p>Controller is using the get_card_details function</p>";
            } else {
                echo "<p>Controller is NOT using the get_card_details function</p>";
            }
            
            // Check if the controller has a private get_default_cards method
            if (preg_match('/private\s+function\s+get_default_cards/i', $controller_content)) {
                echo "<p>Controller has a private get_default_cards method</p>";
            } else {
                echo "<p>Controller does NOT have a private get_default_cards method</p>";
            }
            
            // Check if the controller has a private get_card_details method
            if (preg_match('/private\s+function\s+get_card_details/i', $controller_content)) {
                echo "<p>Controller has a private get_card_details method</p>";
            } else {
                echo "<p>Controller does NOT have a private get_card_details method</p>";
            }
            
            // Check the index method
            if (preg_match('/public\s+function\s+index.*?{.*?}/s', $controller_content, $matches)) {
                echo "<h3>Index Method Analysis</h3>";
                
                $index_method = $matches[0];
                
                // Check if the index method is using the mobile_cards_config table
                if (strpos($index_method, 'mobile_cards_config') !== false) {
                    echo "<p>Index method is using the mobile_cards_config table</p>";
                } else {
                    echo "<p>Index method is NOT using the mobile_cards_config table</p>";
                }
                
                // Check if the index method is using the get_mobile_cards function
                if (strpos($index_method, 'get_mobile_cards') !== false) {
                    echo "<p>Index method is using the get_mobile_cards function</p>";
                } else {
                    echo "<p>Index method is NOT using the get_mobile_cards function</p>";
                }
                
                // Check if the index method is using the get_default_cards function
                if (strpos($index_method, 'get_default_cards') !== false) {
                    echo "<p>Index method is using the get_default_cards function</p>";
                } else {
                    echo "<p>Index method is NOT using the get_default_cards function</p>";
                }
                
                // Check if the index method is using the get_card_details function
                if (strpos($index_method, 'get_card_details') !== false) {
                    echo "<p>Index method is using the get_card_details function</p>";
                } else {
                    echo "<p>Index method is NOT using the get_card_details function</p>";
                }
            } else {
                echo "<p>Could not find the index method in the controller</p>";
            }
        } else {
            echo "<p>Mobilemain controller file not found</p>";
        }
        
        // Check the mobile_cards_helper.php file
        $helper_file = 'application/helpers/mobile_cards_helper.php';
        
        if (file_exists($helper_file)) {
            echo "<h2>Mobile Cards Helper Analysis</h2>";
            
            $helper_content = file_get_contents($helper_file);
            
            // Check if the helper has the get_mobile_cards function
            if (preg_match('/function\s+get_mobile_cards/i', $helper_content)) {
                echo "<p>Helper has the get_mobile_cards function</p>";
            } else {
                echo "<p>Helper does NOT have the get_mobile_cards function</p>";
            }
            
            // Check if the helper has the get_default_cards function
            if (preg_match('/function\s+get_default_cards/i', $helper_content)) {
                echo "<p>Helper has the get_default_cards function</p>";
            } else {
                echo "<p>Helper does NOT have the get_default_cards function</p>";
            }
            
            // Check if the helper has the get_card_details function
            if (preg_match('/function\s+get_card_details/i', $helper_content)) {
                echo "<p>Helper has the get_card_details function</p>";
            } else {
                echo "<p>Helper does NOT have the get_card_details function</p>";
            }
        } else {
            echo "<p>Mobile cards helper file not found</p>";
        }
    }
    
    // Link to direct tools
    echo "<h2>Links</h2>";
    echo "<ul>";
    echo "<li><a href='direct_tools.php'>Direct Tools</a></li>";
    echo "<li><a href='direct_mobilemain.php'>Direct Mobile Main</a></li>";
    echo "<li><a href='direct_settings.php'>Direct Settings</a></li>";
    echo "<li><a href='index.php/mobilemain?force_mobile=1'>Original Mobile Main</a></li>";
    echo "</ul>";
    
} catch(PDOException $e) {
    echo "<p>Database Error: " . $e->getMessage() . "</p>";
}