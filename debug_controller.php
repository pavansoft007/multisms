<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define a simple class to test PHP syntax
class TestController {
    public function __construct() {
        echo "Constructor called successfully.<br>";
    }
    
    public function index() {
        echo "Index method called successfully.<br>";
    }
    
    public function handle_csv_upload() {
        echo "handle_csv_upload method called successfully.<br>";
        return true;
    }
}

// Try to instantiate the class
try {
    $test = new TestController();
    $test->index();
    $test->handle_csv_upload();
    
    echo "<br>PHP version: " . phpversion() . "<br>";
    
    // Check if the null coalescing operator is supported
    $test_var = null;
    $result = $test_var ?? 'default';
    echo "Null coalescing test: " . $result . "<br>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>