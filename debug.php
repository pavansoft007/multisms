<?php
// Debug file to check for errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "PHP Version: " . phpversion() . "<br>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Current Directory: " . getcwd() . "<br>";

// Check if the controller file exists
$controller_path = __DIR__ . '/application/controllers/Mainmenu.php';
echo "Controller Path: " . $controller_path . "<br>";
echo "Controller Exists: " . (file_exists($controller_path) ? 'Yes' : 'No') . "<br>";

// Check if the view files exist
$view_paths = [
    __DIR__ . '/application/views/mainmenu.php',
    __DIR__ . '/application/views/mainmenu_web.php',
    __DIR__ . '/application/views/mainmenu_web_simple.php',
    __DIR__ . '/application/views/mainmenu_standalone.php',
    __DIR__ . '/application/views/layout/index.php'
];

echo "<h3>View Files:</h3>";
foreach ($view_paths as $path) {
    echo basename($path) . " Exists: " . (file_exists($path) ? 'Yes' : 'No') . "<br>";
}

// Check for syntax errors in the controller
echo "<h3>Checking Controller Syntax:</h3>";
$output = [];
$return_var = 0;
exec('php -l ' . $controller_path, $output, $return_var);
echo implode("<br>", $output);

// Try to include the controller file to check for any runtime errors
echo "<h3>Trying to include controller:</h3>";
try {
    include_once($controller_path);
    echo "Controller included successfully.<br>";
} catch (Exception $e) {
    echo "Error including controller: " . $e->getMessage() . "<br>";
}

echo "<h3>Done</h3>";
?>