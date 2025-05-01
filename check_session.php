<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Session Check</h1>";

// Start session
session_start();

// Display session data
echo "<h2>Current Session Data</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Check for specific session keys
echo "<h2>Important Session Keys</h2>";
echo "<ul>";
echo "<li>Role ID: " . (isset($_SESSION['role_id']) ? $_SESSION['role_id'] : 'Not set') . "</li>";
echo "<li>User ID: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not set') . "</li>";
echo "<li>Username: " . (isset($_SESSION['username']) ? $_SESSION['username'] : 'Not set') . "</li>";
echo "<li>Name: " . (isset($_SESSION['name']) ? $_SESSION['name'] : 'Not set') . "</li>";
echo "<li>Logged In: " . (isset($_SESSION['loggedin']) ? ($_SESSION['loggedin'] ? 'Yes' : 'No') : 'Not set') . "</li>";
echo "</ul>";

// Set session data for testing
echo "<h2>Set Session Data</h2>";
echo "<form method='post'>";
echo "<label for='role_id'>Role ID:</label>";
echo "<select name='role_id' id='role_id'>";
echo "<option value='1'>1 - Admin</option>";
echo "<option value='2'>2 - Teacher</option>";
echo "<option value='3'>3 - Student</option>";
echo "<option value='4'>4 - Parent</option>";
echo "<option value='5'>5 - Accountant</option>";
echo "<option value='6'>6 - Librarian</option>";
echo "<option value='7'>7 - Staff</option>";
echo "</select>";
echo "<br><br>";
echo "<input type='submit' name='set_session' value='Set Session'>";
echo "</form>";

// Handle form submission
if (isset($_POST['set_session'])) {
    $role_id = (int)$_POST['role_id'];
    
    $_SESSION['role_id'] = $role_id;
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'test_user';
    $_SESSION['name'] = 'Test User';
    $_SESSION['loggedin'] = true;
    
    echo "<p>Session data set successfully. <a href='check_session.php'>Refresh</a> to see the changes.</p>";
    echo "<p>Now you can <a href='index.php/mobilemain?force_mobile=1'>visit the mobilemain page</a> to test with this role.</p>";
}

// Link to test the mobilemain page
echo "<h2>Test Links</h2>";
echo "<ul>";
echo "<li><a href='index.php/mobilemain?force_mobile=1'>Test Mobilemain Page</a></li>";
echo "<li><a href='index.php/mobilemain/settings'>Test Mobilemain Settings Page</a></li>";
echo "<li><a href='check_mobile_cards.php'>Check Mobile Cards Database</a></li>";
echo "</ul>";