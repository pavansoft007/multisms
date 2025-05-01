<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Get user role ID
$role_id = isset($_SESSION['role_id']) ? (int)$_SESSION['role_id'] : 0;
$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direct Tools for Mobile Cards</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        h1, h2 {
            color: #333;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .card h2 {
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .button:hover {
            background-color: #45a049;
        }
        .status {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .status.logged-in {
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            color: #3c763d;
        }
        .status.logged-out {
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            color: #a94442;
        }
    </style>
</head>
<body>
    <h1>Direct Tools for Mobile Cards</h1>
    
    <div class="status <?php echo $logged_in ? 'logged-in' : 'logged-out'; ?>">
        <strong>Session Status:</strong> <?php echo $logged_in ? 'Logged in' : 'Not logged in'; ?>
        <?php if ($logged_in): ?>
            <br>
            <strong>Role ID:</strong> <?php echo $role_id; ?>
        <?php endif; ?>
    </div>
    
    <div class="card">
        <h2>Session Management</h2>
        <p>Set up a test session to simulate different user roles.</p>
        <a href="check_session.php" class="button">Manage Session</a>
    </div>
    
    <div class="card">
        <h2>Mobile Cards</h2>
        <p>View and configure mobile cards for different roles.</p>
        <a href="direct_mobilemain.php" class="button">View Mobile Main</a>
        <a href="direct_settings.php" class="button">Configure Cards</a>
    </div>
    
    <div class="card">
        <h2>Database Tools</h2>
        <p>Check and fix the database.</p>
        <a href="direct_check_db.php" class="button">Check Database</a>
        <a href="fix_database.php" class="button">Fix Database</a>
    </div>
    
    <div class="card">
        <h2>Original System</h2>
        <p>Access the original system.</p>
        <a href="index.php/mobilemain?force_mobile=1" class="button">Original Mobile Main</a>
        <a href="index.php/mobilemain/settings" class="button">Original Settings</a>
    </div>
</body>
</html>