<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check PHP version
echo "PHP Version: " . phpversion() . "<br>";

// Check if we can access the database
try {
    $conn = new mysqli('localhost', 'root', '', 'multisms');
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
    echo "Database connection successful<br>";
    $conn->close();
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "<br>";
}

// Check if the BulkStudentImport controller file exists
$controller_path = 'application/controllers/BulkStudentImport.php';
if (file_exists($controller_path)) {
    echo "BulkStudentImport.php exists<br>";
    
    // Check file permissions
    echo "File permissions: " . substr(sprintf('%o', fileperms($controller_path)), -4) . "<br>";
    
    // Check file size
    echo "File size: " . filesize($controller_path) . " bytes<br>";
    
    // Check if the file is readable
    echo "File is readable: " . (is_readable($controller_path) ? 'Yes' : 'No') . "<br>";
} else {
    echo "BulkStudentImport.php does not exist<br>";
}

// Check if the view file exists
$view_path = 'application/views/bulkstudentimport/index.php';
if (file_exists($view_path)) {
    echo "bulkstudentimport/index.php view exists<br>";
} else {
    echo "bulkstudentimport/index.php view does not exist<br>";
}

// Check if the MY_Controller.php file exists and contains Admin_Controller
$core_path = 'application/core/MY_Controller.php';
if (file_exists($core_path)) {
    echo "MY_Controller.php exists<br>";
    $content = file_get_contents($core_path);
    if (strpos($content, 'class Admin_Controller') !== false) {
        echo "Admin_Controller class found in MY_Controller.php<br>";
    } else {
        echo "Admin_Controller class NOT found in MY_Controller.php<br>";
    }
} else {
    echo "MY_Controller.php does not exist<br>";
}

// Check if the CSV library exists
$library_path = 'application/libraries/Csvimport.php';
if (file_exists($library_path)) {
    echo "Csvimport.php library exists<br>";
} else {
    echo "Csvimport.php library does not exist<br>";
}

// Check PHP error log
$error_log_path = 'application/logs/log-' . date('Y-m-d') . '.php';
if (file_exists($error_log_path)) {
    echo "Today's error log exists. Last few lines:<br>";
    $log_content = file_get_contents($error_log_path);
    $lines = explode("\n", $log_content);
    $last_lines = array_slice($lines, -20);
    echo "<pre>" . implode("\n", $last_lines) . "</pre>";
} else {
    echo "Today's error log does not exist<br>";
}

echo "Error check completed.";
?>