<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Redirect to the mobile_cards_settings.php file
header('Location: mobile_cards_settings.php');
exit;