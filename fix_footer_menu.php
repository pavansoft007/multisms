<?php
// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define BASEPATH to prevent direct access to CodeIgniter files
define('BASEPATH', true);

// Include database configuration
include 'application/config/database.php';

// Connect to database
$mysqli = new mysqli(
    $db['default']['hostname'],
    $db['default']['username'],
    $db['default']['password'],
    $db['default']['database']
);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "<h1>Footer Menu Database Fix</h1>";

// Check if footer_menu_config table exists
$tableExists = $mysqli->query("SHOW TABLES LIKE 'footer_menu_config'");
if ($tableExists->num_rows == 0) {
    echo "<p>Creating footer_menu_config table...</p>";
    
    // Create the table
    $createTable = "CREATE TABLE `footer_menu_config` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `role_id` int(11) NOT NULL,
        `menu_item` varchar(50) NOT NULL,
        `status` tinyint(1) DEFAULT 1,
        PRIMARY KEY (`id`),
        KEY `role_id` (`role_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    
    if ($mysqli->query($createTable)) {
        echo "<p>Table created successfully.</p>";
    } else {
        echo "<p>Error creating table: " . $mysqli->error . "</p>";
    }
} else {
    echo "<p>Table footer_menu_config already exists.</p>";
    
    // Clear existing data
    echo "<p>Clearing existing data...</p>";
    if ($mysqli->query("TRUNCATE TABLE footer_menu_config")) {
        echo "<p>Existing data cleared.</p>";
    } else {
        echo "<p>Error clearing data: " . $mysqli->error . "</p>";
    }
}

// Get all roles
$roles = $mysqli->query("SELECT * FROM roles");
if ($roles) {
    echo "<p>Found " . $roles->num_rows . " roles.</p>";
    
    // Add default menu items for each role
    while ($role = $roles->fetch_assoc()) {
        echo "<p>Adding menu items for role: " . $role['name'] . " (ID: " . $role['id'] . ")</p>";
        
        // Default menu items for all roles
        $menuItems = array('dashboard', 'message');
        
        // Add role-specific menu items
        if ($role['id'] == 7 || $role['id'] == 6) { // Student or Parent
            $menuItems = array_merge($menuItems, array('homework', 'attendance', 'fees'));
        } else {
            // For admin roles, include all menu items
            $menuItems = array_merge($menuItems, array('students', 'payments', 'attendance', 'homework', 'fees'));
        }
        
        // Insert default configuration
        foreach ($menuItems as $menuItem) {
            $stmt = $mysqli->prepare("INSERT INTO footer_menu_config (role_id, menu_item, status) VALUES (?, ?, 1)");
            $stmt->bind_param("is", $role['id'], $menuItem);
            
            if ($stmt->execute()) {
                echo "<p>- Added menu item: " . $menuItem . "</p>";
            } else {
                echo "<p>- Error adding menu item: " . $stmt->error . "</p>";
            }
            
            $stmt->close();
        }
    }
} else {
    echo "<p>Error getting roles: " . $mysqli->error . "</p>";
}

// Clear cache files
$cachePath = 'application/cache/';
if (is_dir($cachePath)) {
    echo "<p>Clearing cache files...</p>";
    $cacheFiles = glob($cachePath . 'footer_menu_*');
    if (!empty($cacheFiles)) {
        foreach ($cacheFiles as $file) {
            if (is_file($file)) {
                if (unlink($file)) {
                    echo "<p>- Deleted cache file: " . basename($file) . "</p>";
                } else {
                    echo "<p>- Failed to delete cache file: " . basename($file) . "</p>";
                }
            }
        }
    } else {
        echo "<p>No cache files found.</p>";
    }
}

// Add JavaScript to force refresh client-side cache
echo "<script>
    // Set a timestamp to force browser to reload scripts
    localStorage.setItem('cache_timestamp', Date.now());
    
    // Add body classes for testing
    document.addEventListener('DOMContentLoaded', function() {
        // Add a test button to reload the page with role classes
        var testDiv = document.createElement('div');
        testDiv.style.margin = '20px 0';
        testDiv.innerHTML = '<h3>Test Footer Menu</h3>' +
            '<p>Click a button below to test the footer menu for a specific role:</p>' +
            '<button onclick=\"testRole(1)\" style=\"margin:5px;padding:5px 10px\">Superadmin (1)</button> ' +
            '<button onclick=\"testRole(2)\" style=\"margin:5px;padding:5px 10px\">Admin (2)</button> ' +
            '<button onclick=\"testRole(3)\" style=\"margin:5px;padding:5px 10px\">Teacher (3)</button> ' +
            '<button onclick=\"testRole(6)\" style=\"margin:5px;padding:5px 10px\">Parent (6)</button> ' +
            '<button onclick=\"testRole(7)\" style=\"margin:5px;padding:5px 10px\">Student (7)</button>';
        
        document.body.appendChild(testDiv);
    });
    
    function testRole(roleId) {
        // Store role ID in localStorage
        localStorage.setItem('test_role_id', roleId);
        
        // Redirect to test page
        window.location.href = 'test_footer_menu.php?role_id=' + roleId;
    }
</script>";

echo "<p>Database fix completed.</p>";
echo "<p><a href='settings_footer' style='display:inline-block;margin:10px 0;padding:10px 15px;background:#3A3978;color:#fff;text-decoration:none;border-radius:4px;'>Go to Footer Settings</a></p>";

// Close connection
$mysqli->close();
?>