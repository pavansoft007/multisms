<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<h1>Error: Not logged in</h1>";
    echo "<p>Please <a href='check_session.php'>set a session</a> first.</p>";
    exit;
}

// Get user role ID
$role_id = isset($_SESSION['role_id']) ? (int)$_SESSION['role_id'] : 0;

echo "<h1>Mobile Main Page (Direct Version)</h1>";
echo "<p>User Role ID: $role_id</p>";

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
    
    // Get enabled cards for this role
    $stmt = $db->prepare("SELECT card_item FROM mobile_cards_config WHERE role_id = ? AND status = 1 ORDER BY card_order ASC");
    $stmt->execute([$role_id]);
    
    $card_items = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $card_items[] = $row['card_item'];
    }
    
    echo "<p>Found " . count($card_items) . " enabled cards for role $role_id</p>";
    
    // If no cards found, use default cards
    if (empty($card_items)) {
        // Check if this role has ANY configuration (even disabled cards)
        $stmt = $db->prepare("SELECT COUNT(*) FROM mobile_cards_config WHERE role_id = ?");
        $stmt->execute([$role_id]);
        $has_config = ($stmt->fetchColumn() > 0);
        
        if ($has_config) {
            echo "<p>This role has configuration but all cards are disabled</p>";
        } else {
            echo "<p>No configuration found for this role, using default cards</p>";
            
            // Default cards for all roles
            $default_cards = array('profile', 'settings');
            
            // Role-specific default cards
            if ($role_id == 7) { // Student
                $student_cards = array('attendance', 'fees', 'homework', 'marks', 'events');
                $card_items = array_merge($student_cards, $default_cards);
            } else if ($role_id == 6) { // Parent
                $parent_cards = array('attendance', 'fees', 'homework', 'marks', 'events');
                $card_items = array_merge($parent_cards, $default_cards);
            } else if ($role_id == 3) { // Teacher
                $teacher_cards = array('students', 'attendance', 'classes', 'homework', 'marks', 'events');
                $card_items = array_merge($teacher_cards, $default_cards);
            } else { // Admin and Superadmin
                $admin_cards = array('students', 'attendance', 'fees', 'classes', 'homework', 'marks', 'events', 'reports');
                $card_items = array_merge($admin_cards, $default_cards);
            }
        }
    }
    
    // Function to get card details
    function get_card_details($card_item, $role_id) {
        // Default card details
        $card_details = array(
            'icon' => 'help',
            'title' => ucfirst($card_item),
            'url' => 'index.php/' . $card_item,
            'color' => $card_item // Use the card item as the color class
        );
        
        switch ($card_item) {
            case 'students':
                $card_details = array(
                    'icon' => 'school',
                    'title' => 'Students',
                    'url' => 'index.php/student',
                    'color' => 'students'
                );
                break;
            case 'attendance':
                $url = ($role_id == 7 || $role_id == 6) ? 'index.php/userrole/attendance' : 'index.php/attendance';
                $card_details = array(
                    'icon' => 'how_to_reg',
                    'title' => 'Attendance',
                    'url' => $url,
                    'color' => 'attendance'
                );
                break;
            case 'fees':
                $url = ($role_id == 7 || $role_id == 6) ? 'index.php/userrole/invoice' : 'index.php/fees';
                $card_details = array(
                    'icon' => 'attach_money',
                    'title' => 'Fees',
                    'url' => $url,
                    'color' => 'fees'
                );
                break;
            case 'classes':
                $card_details = array(
                    'icon' => 'class',
                    'title' => 'Classes',
                    'url' => 'index.php/classes',
                    'color' => 'classes'
                );
                break;
            case 'homework':
                $url = ($role_id == 7 || $role_id == 6) ? 'index.php/userrole/homework' : 'index.php/homework';
                $card_details = array(
                    'icon' => 'assignment',
                    'title' => 'Homework',
                    'url' => $url,
                    'color' => 'homeworks' // Keep this for consistency with CSS
                );
                break;
            case 'marks':
                $url = ($role_id == 7 || $role_id == 6) ? 'index.php/userrole/exam' : 'index.php/exam';
                $card_details = array(
                    'icon' => 'grading',
                    'title' => 'Marks',
                    'url' => $url,
                    'color' => 'marks'
                );
                break;
            case 'events':
                $card_details = array(
                    'icon' => 'event',
                    'title' => 'Events',
                    'url' => 'index.php/event',
                    'color' => 'events'
                );
                break;
            case 'reports':
                $card_details = array(
                    'icon' => 'analytics',
                    'title' => 'Reports',
                    'url' => 'index.php/dashboard/reports',
                    'color' => 'reports'
                );
                break;
            case 'profile':
                $card_details = array(
                    'icon' => 'person',
                    'title' => 'Profile',
                    'url' => 'index.php/profile',
                    'color' => 'profile'
                );
                break;
            case 'settings':
                $card_details = array(
                    'icon' => 'settings',
                    'title' => 'Settings',
                    'url' => 'index.php/settings',
                    'color' => 'settings'
                );
                break;
        }
        
        return $card_details;
    }
    
    // Prepare cards data
    $cards = array();
    foreach ($card_items as $card_item) {
        // Skip the 'none' card item (used as a placeholder)
        if ($card_item !== 'none') {
            $cards[] = get_card_details($card_item, $role_id);
        }
    }
    
    // Display the cards
    echo "<h2>Cards for Role $role_id</h2>";
    
    if (empty($cards)) {
        echo "<p>No cards to display</p>";
    } else {
        echo "<div style='display: flex; flex-wrap: wrap;'>";
        
        foreach ($cards as $card) {
            echo "<div style='width: 200px; margin: 10px; padding: 15px; border-radius: 5px; background-color: #f0f0f0;'>";
            echo "<h3>{$card['title']}</h3>";
            echo "<p>Icon: {$card['icon']}</p>";
            echo "<p><a href='{$card['url']}'>Open</a></p>";
            echo "</div>";
        }
        
        echo "</div>";
    }
    
    // Link to settings page
    echo "<p><a href='index.php/mobilemain/settings'>Configure Cards</a></p>";
    
    // Link to check session
    echo "<p><a href='check_session.php'>Change Session</a></p>";
    
    // Link to check database
    echo "<p><a href='check_mobile_cards.php'>Check Database</a></p>";
    
} catch(PDOException $e) {
    echo "<p>Database Error: " . $e->getMessage() . "</p>";
}