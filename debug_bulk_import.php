<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include CodeIgniter bootstrap file
include_once('./index.php');

// Try to manually load the controller
try {
    // Get CI instance
    $CI = &get_instance();
    
    // Load required models
    $CI->load->model('application_model');
    $CI->load->model('student_model');
    $CI->load->model('email_model');
    $CI->load->model('sms_model');
    $CI->load->model('parents_model');
    
    echo "Models loaded successfully.<br>";
    
    // Check if the controller file exists
    if (file_exists(APPPATH . 'controllers/BulkStudentImport.php')) {
        echo "BulkStudentImport.php file exists.<br>";
    } else {
        echo "BulkStudentImport.php file does not exist!<br>";
    }
    
    // Check if the view file exists
    if (file_exists(APPPATH . 'views/bulkstudentimport/index.php')) {
        echo "bulkstudentimport/index.php view file exists.<br>";
    } else {
        echo "bulkstudentimport/index.php view file does not exist!<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>