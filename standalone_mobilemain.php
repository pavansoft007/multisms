<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define ENVIRONMENT constant if not defined
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}

// Start session
session_start();

// Check if the user is logged in
$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
if (!$logged_in) {
    // Redirect to authentication
    header('Location: index.php/authentication');
    exit;
}

// Get user role ID
$role_id = isset($_SESSION['role_id']) ? (int)$_SESSION['role_id'] : 0;
$user_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'User';

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
    
    // Get mobile cards for this role
    function get_mobile_cards_direct($db, $role_id) {
        // Check if table exists to prevent errors
        $stmt = $db->query("SHOW TABLES LIKE 'mobile_cards_config'");
        if ($stmt->rowCount() == 0) {
            return array(); // Return empty array if table doesn't exist
        }
        
        // IMPORTANT: Force integer type for role_id to ensure proper comparison
        $role_id = (int)$role_id;
        
        // Get from database directly
        $stmt = $db->prepare("SELECT card_item FROM mobile_cards_config WHERE role_id = ? AND status = 1 ORDER BY card_order ASC");
        $stmt->execute([$role_id]);
        
        $card_items = array();
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $card_items[] = $row['card_item'];
            }
        } else {
            // Check if this role has ANY configuration (even disabled cards)
            $stmt = $db->prepare("SELECT COUNT(*) FROM mobile_cards_config WHERE role_id = ?");
            $stmt->execute([$role_id]);
            $has_config = ($stmt->fetchColumn() > 0);
            
            if ($has_config) {
                // This role has configuration but all cards are disabled
                $card_items = array(); // Empty array - no cards to show
            } else {
                // No configuration found for this role, use defaults
                
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
        
        return $card_items;
    }
    
    // Get card details
    function get_card_details_direct($card_item, $role_id = 0) {
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
    
    // Get card items for this role
    $card_items = get_mobile_cards_direct($db, $role_id);
    
    // Prepare cards data
    $cards = array();
    foreach ($card_items as $card_item) {
        // Skip the 'none' card item (used as a placeholder)
        if ($card_item !== 'none') {
            $cards[] = get_card_details_direct($card_item, $role_id);
        }
    }
    
    // Output the mobile main page
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>Mobile Dashboard (Standalone)</title>
        <link rel="shortcut icon" href="assets/images/favicon.png">
        <!-- Material Design Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
        <!-- Material Icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="assets/vendor/font-awesome/css/all.min.css">
        <style>
            body {
                font-family: 'Roboto', sans-serif;
                background-color: #f5f5f5; /* Light gray background */
                margin: 0;
                padding: 0;
                padding-bottom: 70px; /* Add padding to prevent content from being hidden by the bottom nav */
            }
            .mobile-header {
                background-color: #0091cd;
                color: white;
                padding: 16px;
                text-align: center;
                position: relative;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .mobile-header h1 {
                margin: 0;
                font-size: 20px;
                font-weight: 500;
            }
            .mobile-header .profile-icon {
                position: absolute;
                right: 16px;
                top: 50%;
                transform: translateY(-50%);
                font-size: 24px;
            }
            .mobile-header .back-icon {
                position: absolute;
                left: 16px;
                top: 50%;
                transform: translateY(-50%);
                font-size: 24px;
            }
            .mobile-welcome {
                padding: 20px;
                background-color: white;
                margin-bottom: 16px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            .mobile-welcome h2 {
                margin: 0;
                font-size: 18px;
                font-weight: 500;
                color: #333333;
            }
            .mobile-welcome p {
                margin: 8px 0 0;
                font-size: 14px;
                color: #666;
            }
            /* Mobile cards grid */
            .mobile-cards-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 16px;
                padding: 20px;
            }
            .mobile-card {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                background-color: white;
                border-radius: 8px;
                padding: 20px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                text-decoration: none;
                color: #333;
                transition: transform 0.2s, box-shadow 0.2s;
                text-align: center;
                height: 150px;
            }
            .mobile-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            }
            .card-icon {
                font-size: 48px;
                margin-bottom: 12px;
                color: #0091cd;
            }
            .card-title {
                margin: 0;
                font-size: 16px;
                font-weight: 500;
            }
            /* Card colors */
            .mobile-card.students .card-icon { color: #4CAF50; }
            .mobile-card.attendance .card-icon { color: #2196F3; }
            .mobile-card.fees .card-icon { color: #F44336; }
            .mobile-card.classes .card-icon { color: #9C27B0; }
            .mobile-card.homeworks .card-icon { color: #FF9800; }
            .mobile-card.marks .card-icon { color: #795548; }
            .mobile-card.events .card-icon { color: #607D8B; }
            .mobile-card.reports .card-icon { color: #009688; }
            .mobile-card.profile .card-icon { color: #673AB7; }
            .mobile-card.settings .card-icon { color: #757575; }
            
            /* No cards message */
            .no-cards {
                text-align: center;
                padding: 40px 20px;
                color: #757575;
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                margin: 16px;
            }
            .no-cards .material-icons {
                font-size: 48px;
                margin-bottom: 16px;
                color: #bbbbbb;
            }
            
            /* Bottom navigation */
            .bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                display: flex;
                background: #ffffff;
                box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.1);
                z-index: 1000;
            }
            .bottom-nav a {
                flex: 1;
                padding: 12px 0;
                background: none;
                border: none;
                font-size: 14px;
                color: #757575;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                transition: color 0.3s;
                text-decoration: none;
            }
            .bottom-nav a.active {
                color: #0091cd;
            }
            .bottom-nav a:hover {
                color: #0091cd;
            }
            .material-icons {
                font-size: 24px;
                margin-bottom: 4px;
            }
            
            /* Standalone banner */
            .standalone-banner {
                background-color: #FFC107;
                color: #333;
                padding: 10px;
                text-align: center;
                font-weight: bold;
            }
            .standalone-banner a {
                color: #0066cc;
                text-decoration: underline;
            }
        </style>
    </head>
    <body data-role-id="<?php echo $role_id; ?>">
        <div class="standalone-banner">
            Standalone Version | <a href="direct_tools.php">Tools</a> | <a href="direct_settings.php">Settings</a> | <a href="check_session.php">Session</a>
        </div>
        
        <div class="mobile-header">
            <a href="index.php/dashboard" class="back-icon text-white">
                <i class="material-icons">arrow_back</i>
            </a>
            <h1>Mobile Dashboard (Standalone)</h1>
            <a href="index.php/profile" class="profile-icon text-white">
                <i class="material-icons">person</i>
            </a>
        </div>
        
        <div class="mobile-welcome">
            <h2>Welcome, <?php echo $user_name; ?></h2>
            <p>Access your most important features below</p>
        </div>
        
        <?php if (!empty($cards)): ?>
        <div class="mobile-cards-grid">
            <?php foreach ($cards as $card): ?>
            <a href="<?php echo $card['url']; ?>" class="mobile-card <?php echo $card['color']; ?>">
                <i class="material-icons card-icon"><?php echo $card['icon']; ?></i>
                <h3 class="card-title"><?php echo $card['title']; ?></h3>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="no-cards">
            <span class="material-icons">dashboard_customize</span>
            <p>No cards configured for your role. Please contact the administrator.</p>
        </div>
        <?php endif; ?>
        
        <div class="bottom-nav">
            <a href="standalone_mobilemain.php" class="active">
                <span class="material-icons">dashboard</span>
                <div>Dashboard</div>
            </a>
            <a href="index.php/attendance">
                <span class="material-icons">how_to_reg</span>
                <div>Attendance</div>
            </a>
            <a href="index.php/fees">
                <span class="material-icons">attach_money</span>
                <div>Fees</div>
            </a>
            <a href="direct_tools.php">
                <span class="material-icons">menu</span>
                <div>More</div>
            </a>
        </div>
        
        <!-- jQuery -->
        <script src="assets/vendor/jquery/jquery.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
    </body>
    </html>
    <?php
} catch(PDOException $e) {
    echo "<h1>Database Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p><a href='check_session.php'>Back to Session Management</a></p>";
}