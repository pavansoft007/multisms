<?php
// Set up a test session
session_start();

// Set session variables
$_SESSION['role_id'] = 1; // Admin role
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'admin';
$_SESSION['name'] = 'Administrator';
$_SESSION['loggedin'] = true;

// Redirect to mobilemain
header('Location: index.php/mobilemain?force_mobile=1');
exit;