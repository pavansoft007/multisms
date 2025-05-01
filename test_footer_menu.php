<?php
// Test page for footer menu
// This script will simulate a logged-in user with a specific role ID

// Get role ID from query string
$role_id = isset($_GET['role_id']) ? intval($_GET['role_id']) : 0;

// Define role names
$role_names = array(
    1 => 'Superadmin',
    2 => 'Admin',
    3 => 'Teacher',
    6 => 'Parent',
    7 => 'Student'
);

// Define role types
$role_types = array(
    1 => 'superadmin',
    2 => 'admin',
    3 => 'teacher',
    6 => 'parent',
    7 => 'student'
);

// Get role name
$role_name = isset($role_names[$role_id]) ? $role_names[$role_id] : 'Unknown';
$role_type = isset($role_types[$role_id]) ? $role_types[$role_id] : '';

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

// Get menu items for this role
$menu_items = array();
if ($role_id > 0) {
    $stmt = $mysqli->prepare("SELECT menu_item FROM footer_menu_config WHERE role_id = ? AND status = 1");
    $stmt->bind_param("i", $role_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $menu_items[] = $row['menu_item'];
    }
    
    $stmt->close();
}

// Close connection
$mysqli->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Footer Menu - <?php echo $role_name; ?></title>
    <link rel="stylesheet" href="assets/vendor/font-awesome/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h1 {
            color: #3A3978;
            margin-top: 0;
        }
        .role-info {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .menu-items {
            margin-bottom: 20px;
        }
        .menu-item {
            background: #eef5ff;
            padding: 10px 15px;
            margin-bottom: 5px;
            border-radius: 3px;
        }
        .mobile-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .mobile-footer-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            justify-content: space-around;
        }
        .mobile-footer-menu li {
            flex: 1;
            text-align: center;
        }
        .mobile-footer-menu li a {
            display: block;
            padding: 10px 5px;
            color: #666;
            text-decoration: none;
            font-size: 12px;
        }
        .mobile-footer-menu li a i {
            display: block;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .mobile-footer-menu li a.active {
            color: #3A3978;
        }
        .buttons {
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background: #3A3978;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
        }
        .btn-secondary {
            background: #6c757d;
        }
    </style>
</head>
<body class="<?php echo $role_type; ?>-logged-in">
    <div class="container">
        <h1>Test Footer Menu</h1>
        
        <div class="role-info">
            <h3>Role Information</h3>
            <p><strong>Role ID:</strong> <?php echo $role_id; ?></p>
            <p><strong>Role Name:</strong> <?php echo $role_name; ?></p>
            <p><strong>Role Type:</strong> <?php echo $role_type; ?></p>
            <p><strong>Body Class:</strong> <?php echo $role_type; ?>-logged-in</p>
        </div>
        
        <div class="menu-items">
            <h3>Menu Items from Database</h3>
            <?php if (empty($menu_items)): ?>
                <p>No menu items found for this role.</p>
            <?php else: ?>
                <?php foreach ($menu_items as $item): ?>
                    <div class="menu-item"><?php echo $item; ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="buttons">
            <a href="fix_footer_menu.php" class="btn">Back to Fix Script</a>
            <a href="settings_footer" class="btn">Go to Footer Settings</a>
        </div>
    </div>
    
    <!-- Mobile Footer -->
    <div class="mobile-footer">
        <ul class="mobile-footer-menu">
            <!-- Footer menu will be loaded via JavaScript -->
        </ul>
    </div>
    
    <!-- Scripts -->
    <script src="assets/vendor/jquery/jquery.js"></script>
    <script>
        // Define variables needed by the footer scripts
        var base_url = "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/multisms/'; ?>";
        var loggedin_role_id = <?php echo $role_id; ?>;
        var user_type = "<?php echo $role_type; ?>";
        
        // Define translation object for mobile footer
        var translate = {
            dashboard: "Dashboard",
            homework: "Homework",
            attendance: "Attendance",
            fees: "Fees",
            students: "Students",
            payments: "Payments",
            message: "Message"
        };
        
        // Function to set active menu item
        function setActiveMenuItem() {
            var currentPath = window.location.pathname;
            $('.mobile-footer-menu li a').each(function() {
                var linkPath = $(this).attr('href');
                if (currentPath.indexOf(linkPath) !== -1) {
                    $(this).addClass('active');
                }
            });
        }
        
        // Function to reload the mobile footer
        function reloadMobileFooter() {
            // Get the current mobile footer
            var $mobileFooter = $('.mobile-footer');
            
            if ($mobileFooter.length) {
                // Only proceed if we have a valid role ID
                if (loggedin_role_id > 0) {
                    // Make AJAX request to get updated menu items
                    $.ajax({
                        url: base_url + 'settings_footer/get_menu_items',
                        type: 'POST',
                        data: {
                            role_id: loggedin_role_id
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log('AJAX response:', response);
                            
                            // Clear existing menu
                            $('.mobile-footer-menu').empty();
                            
                            // Build new menu based on selected items
                            var menuItems = response.selected_items;
                            
                            // If no items selected, use default items
                            if (menuItems.length === 0) {
                                menuItems = ['dashboard'];
                                
                                if (loggedin_role_id === 7 || loggedin_role_id === 6) {
                                    menuItems.push('homework');
                                    menuItems.push('attendance');
                                    menuItems.push('fees');
                                } else {
                                    menuItems.push('students');
                                    menuItems.push('payments');
                                    menuItems.push('attendance');
                                    menuItems.push('homework');
                                    menuItems.push('fees');
                                }
                                
                                menuItems.push('message');
                            }
                            
                            // Build menu HTML
                            var menuHtml = '';
                            
                            // Loop through menu items
                            $.each(menuItems, function(index, item) {
                                switch (item) {
                                    case 'dashboard':
                                        menuHtml += '<li><a href="' + base_url + 'dashboard"><i class="fas fa-th-large"></i><span>' + translate.dashboard + '</span></a></li>';
                                        break;
                                    case 'homework':
                                        // Show homework menu item for all roles if it's assigned
                                        var homeworkUrl = (loggedin_role_id === 7 || loggedin_role_id === 6) ? 'userrole/homework' : 'homework';
                                        menuHtml += '<li><a href="' + base_url + homeworkUrl + '"><i class="fas fa-book"></i><span>' + translate.homework + '</span></a></li>';
                                        break;
                                    case 'attendance':
                                        if (loggedin_role_id === 7 || loggedin_role_id === 6) {
                                            menuHtml += '<li><a href="' + base_url + 'userrole/attendance"><i class="fas fa-chart-bar"></i><span>' + translate.attendance + '</span></a></li>';
                                        } else {
                                            menuHtml += '<li><a href="' + base_url + 'attendance"><i class="fas fa-chart-bar"></i><span>' + translate.attendance + '</span></a></li>';
                                        }
                                        break;
                                    case 'fees':
                                        // Show fees menu item for all roles if it's assigned
                                        var feesUrl = (loggedin_role_id === 7 || loggedin_role_id === 6) ? 'userrole/invoice' : 'fees/invoice_list';
                                        menuHtml += '<li><a href="' + base_url + feesUrl + '"><i class="fas fa-calculator"></i><span>' + translate.fees + '</span></a></li>';
                                        break;
                                    case 'students':
                                        if (loggedin_role_id !== 7 && loggedin_role_id !== 6) {
                                            menuHtml += '<li><a href="' + base_url + 'student/view"><i class="fas fa-user-graduate"></i><span>' + translate.students + '</span></a></li>';
                                        }
                                        break;
                                    case 'payments':
                                        if (loggedin_role_id !== 7 && loggedin_role_id !== 6) {
                                            menuHtml += '<li><a href="' + base_url + 'fees/invoice_list"><i class="fas fa-file-invoice"></i><span>' + translate.payments + '</span></a></li>';
                                        }
                                        break;
                                    case 'message':
                                        menuHtml += '<li><a href="' + base_url + 'communication/mailbox/inbox"><i class="fas fa-envelope"></i><span>' + translate.message + '</span></a></li>';
                                        break;
                                }
                            });
                            
                            // Add menu HTML to footer
                            $('.mobile-footer-menu').html(menuHtml);
                            
                            // Set active menu item
                            setActiveMenuItem();
                            
                            // Add debug info to page
                            var debugInfo = $('<div class="menu-items">')
                                .append('<h3>Menu Items from AJAX Response</h3>');
                            
                            if (menuItems.length === 0) {
                                debugInfo.append('<p>No menu items returned from AJAX.</p>');
                            } else {
                                $.each(menuItems, function(index, item) {
                                    debugInfo.append('<div class="menu-item">' + item + '</div>');
                                });
                            }
                            
                            $('.container').append(debugInfo);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading menu items:', error);
                            $('.container').append(
                                '<div class="menu-items">' +
                                '<h3>AJAX Error</h3>' +
                                '<p>Error loading menu items: ' + error + '</p>' +
                                '</div>'
                            );
                        }
                    });
                }
            }
        }
        
        // Initialize on document ready
        $(document).ready(function() {
            // Reload mobile footer on page load
            reloadMobileFooter();
        });
    </script>
</body>
</html>