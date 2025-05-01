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

// Check if the user is admin
$role_id = isset($_SESSION['role_id']) ? (int)$_SESSION['role_id'] : 0;
if ($role_id != 1 && $role_id != 2) { // Only admin and superadmin can access
    header('Location: mobilemain.php');
    exit;
}

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
    
    // Create the mobile_cards_config table if it doesn't exist
    $check_table = $db->query("SHOW TABLES LIKE 'mobile_cards_config'");
    if ($check_table->rowCount() == 0) {
        $create_table = "CREATE TABLE `mobile_cards_config` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `role_id` int(11) NOT NULL,
            `card_item` varchar(50) NOT NULL,
            `card_order` int(11) NOT NULL DEFAULT 0,
            `status` tinyint(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (`id`),
            KEY `role_id` (`role_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        $db->exec($create_table);
        echo "<p>Created mobile_cards_config table</p>";
    }
    
    // Define roles
    $roles = array(
        1 => 'Admin',
        2 => 'Superadmin',
        3 => 'Teacher',
        4 => 'Accountant',
        5 => 'Librarian',
        6 => 'Parent',
        7 => 'Student'
    );
    
    // Define all available card items
    $all_cards = array(
        'students' => 'Students',
        'attendance' => 'Attendance',
        'fees' => 'Fees',
        'classes' => 'Classes',
        'homework' => 'Homework',
        'marks' => 'Marks',
        'events' => 'Events',
        'reports' => 'Reports',
        'profile' => 'Profile',
        'settings' => 'Settings'
    );
    
    // Process form submission
    $message = '';
    $message_type = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
        $selected_role_id = (int)$_POST['role_id'];
        $card_items = isset($_POST['card_items']) ? $_POST['card_items'] : array();
        
        // Start a transaction
        $db->beginTransaction();
        
        try {
            // Delete existing configuration for this role
            $stmt = $db->prepare("DELETE FROM mobile_cards_config WHERE role_id = ?");
            $stmt->execute([$selected_role_id]);
            
            // Insert new configuration
            if (!empty($card_items)) {
                $order = 1;
                $stmt = $db->prepare("INSERT INTO mobile_cards_config (role_id, card_item, card_order, status) VALUES (?, ?, ?, 1)");
                
                foreach ($card_items as $card_item) {
                    $stmt->execute([$selected_role_id, $card_item, $order]);
                    $order++;
                }
                
                $message = "Settings saved successfully for " . $roles[$selected_role_id] . "!";
                $message_type = "success";
            } else {
                // Insert a dummy record to indicate that this role has been configured
                // This prevents the system from using default cards
                $stmt = $db->prepare("INSERT INTO mobile_cards_config (role_id, card_item, card_order, status) VALUES (?, 'none', 1, 0)");
                $stmt->execute([$selected_role_id]);
                
                $message = "No cards selected for " . $roles[$selected_role_id] . ". All cards have been disabled.";
                $message_type = "warning";
            }
            
            // Commit the transaction
            $db->commit();
        } catch (Exception $e) {
            // Rollback the transaction
            $db->rollBack();
            
            $message = "Error: " . $e->getMessage();
            $message_type = "danger";
        }
    }
    
    // Get selected role ID from query string or form
    $selected_role_id = isset($_GET['role_id']) ? (int)$_GET['role_id'] : 1;
    if (isset($_POST['role_id'])) {
        $selected_role_id = (int)$_POST['role_id'];
    }
    
    // Get selected card items for this role
    $stmt = $db->prepare("SELECT card_item FROM mobile_cards_config WHERE role_id = ? AND status = 1 ORDER BY card_order ASC");
    $stmt->execute([$selected_role_id]);
    
    $selected_items = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $selected_items[] = $row['card_item'];
    }
    
    // Output the settings page
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>Mobile Cards Settings</title>
        <link rel="shortcut icon" href="assets/images/favicon.png">
        <!-- Material Design Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
        <!-- Material Icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="assets/vendor/font-awesome/css/all.min.css">
        <!-- jQuery UI CSS -->
        <link rel="stylesheet" href="assets/vendor/jquery-ui/jquery-ui.min.css">
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
            .container {
                max-width: 800px;
                margin: 20px auto;
                padding: 20px;
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .form-group {
                margin-bottom: 15px;
            }
            .btn-primary {
                background-color: #0091cd;
                border-color: #0091cd;
            }
            .btn-danger {
                background-color: #dc3545;
                border-color: #dc3545;
            }
            
            /* Card items */
            .card-items-container {
                margin-top: 20px;
            }
            .card-items-list {
                list-style: none;
                padding: 0;
                margin: 0;
                min-height: 50px;
                border: 1px dashed #ccc;
                border-radius: 4px;
                padding: 10px;
            }
            .card-item {
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 4px;
                padding: 10px 15px;
                margin-bottom: 10px;
                cursor: move;
                display: flex;
                align-items: center;
            }
            .card-item:last-child {
                margin-bottom: 0;
            }
            .card-item .material-icons {
                margin-right: 10px;
                color: #0091cd;
            }
            .card-item-placeholder {
                background-color: #f0f8ff;
                border: 1px dashed #0091cd;
                border-radius: 4px;
                padding: 10px 15px;
                margin-bottom: 10px;
                height: 42px;
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
            
            /* Alert messages */
            .alert {
                margin-bottom: 20px;
            }
            
            /* Card icons */
            .icon-students { color: #4CAF50; }
            .icon-attendance { color: #2196F3; }
            .icon-fees { color: #F44336; }
            .icon-classes { color: #9C27B0; }
            .icon-homework { color: #FF9800; }
            .icon-marks { color: #795548; }
            .icon-events { color: #607D8B; }
            .icon-reports { color: #009688; }
            .icon-profile { color: #673AB7; }
            .icon-settings { color: #757575; }
        </style>
    </head>
    <body>
        <div class="mobile-header">
            <a href="mobilemain.php" class="back-icon text-white">
                <i class="material-icons">arrow_back</i>
            </a>
            <h1>Mobile Cards Settings</h1>
            <a href="index.php/profile" class="profile-icon text-white">
                <i class="material-icons">person</i>
            </a>
        </div>
        
        <div class="container">
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $message_type; ?>" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <h2>Configure Mobile Cards</h2>
            <p>Drag and drop cards to customize the mobile dashboard for each role.</p>
            
            <form method="post" action="">
                <div class="form-group">
                    <label for="role_id">Select Role:</label>
                    <select class="form-control" id="role_id" name="role_id" onchange="this.form.submit()">
                        <?php foreach ($roles as $id => $role): ?>
                            <option value="<?php echo $id; ?>" <?php echo ($id == $selected_role_id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($role); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="card-items-container">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Available Cards</h3>
                            <p>Drag cards from here to the selected cards list.</p>
                            <ul id="available-cards" class="card-items-list">
                                <?php foreach ($all_cards as $card_key => $card_name): ?>
                                    <?php if (!in_array($card_key, $selected_items)): ?>
                                        <li class="card-item" data-card="<?php echo $card_key; ?>">
                                            <i class="material-icons icon-<?php echo $card_key; ?>"><?php echo get_card_icon($card_key); ?></i>
                                            <?php echo htmlspecialchars($card_name); ?>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h3>Selected Cards</h3>
                            <p>Cards will appear in this order on the mobile dashboard.</p>
                            <ul id="selected-cards" class="card-items-list">
                                <?php foreach ($selected_items as $card_key): ?>
                                    <?php if (isset($all_cards[$card_key])): ?>
                                        <li class="card-item" data-card="<?php echo $card_key; ?>">
                                            <i class="material-icons icon-<?php echo $card_key; ?>"><?php echo get_card_icon($card_key); ?></i>
                                            <?php echo htmlspecialchars($all_cards[$card_key]); ?>
                                            <input type="hidden" name="card_items[]" value="<?php echo $card_key; ?>">
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mt-4">
                    <button type="submit" name="save" class="btn btn-primary">Save Settings</button>
                    <a href="mobilemain.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
        
        <div class="bottom-nav">
            <a href="mobilemain.php">
                <span class="material-icons">dashboard</span>
                <div>Dashboard</div>
            </a>
            <a href="mobile_cards_settings.php" class="active">
                <span class="material-icons">settings</span>
                <div>Settings</div>
            </a>
            <a href="index.php/profile">
                <span class="material-icons">person</span>
                <div>Profile</div>
            </a>
            <a href="check_session.php">
                <span class="material-icons">help</span>
                <div>Help</div>
            </a>
        </div>
        
        <!-- jQuery -->
        <script src="assets/vendor/jquery/jquery.min.js"></script>
        <!-- jQuery UI -->
        <script src="assets/vendor/jquery-ui/jquery-ui.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
        
        <script>
            $(function() {
                // Make the card items sortable
                $("#available-cards, #selected-cards").sortable({
                    connectWith: ".card-items-list",
                    placeholder: "card-item-placeholder",
                    update: function(event, ui) {
                        // When an item is moved to the selected cards list, add a hidden input
                        if (ui.item.parent().attr('id') === 'selected-cards') {
                            var cardKey = ui.item.data('card');
                            if (ui.item.find('input[name="card_items[]"]').length === 0) {
                                ui.item.append('<input type="hidden" name="card_items[]" value="' + cardKey + '">');
                            }
                        } else {
                            // When an item is moved to the available cards list, remove the hidden input
                            ui.item.find('input[name="card_items[]"]').remove();
                        }
                    }
                }).disableSelection();
            });
        </script>
    </body>
    </html>
    <?php
    
    // Helper function to get the icon for a card
    function get_card_icon($card_key) {
        switch ($card_key) {
            case 'students': return 'school';
            case 'attendance': return 'how_to_reg';
            case 'fees': return 'attach_money';
            case 'classes': return 'class';
            case 'homework': return 'assignment';
            case 'marks': return 'grading';
            case 'events': return 'event';
            case 'reports': return 'analytics';
            case 'profile': return 'person';
            case 'settings': return 'settings';
            default: return 'help';
        }
    }
} catch(PDOException $e) {
    echo "<h1>Database Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p><a href='mobilemain.php'>Back to Mobile Dashboard</a></p>";
}