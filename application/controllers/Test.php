<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        echo 'Hello from Test controller!';
        echo '<br>PHP version: ' . phpversion();
        
        // Test if we can load models
        try {
            $this->load->model('application_model');
            echo '<br>Application model loaded successfully.';
        } catch (Exception $e) {
            echo '<br>Error loading application model: ' . $e->getMessage();
        }
    }
    
    public function bulk_import() {
        // Test if we can load the BulkStudentImport controller
        try {
            $this->load->library('csvimport');
            echo 'CSV Import library loaded successfully.<br>';
            
            // Check if the controller file exists
            if (file_exists(APPPATH . 'controllers/BulkStudentImport.php')) {
                echo 'BulkStudentImport.php file exists.<br>';
                
                // Include the controller file
                require_once(APPPATH . 'controllers/BulkStudentImport.php');
                
                // Try to instantiate the controller
                $bulk_import = new BulkStudentImport();
                echo 'BulkStudentImport controller instantiated successfully.<br>';
            } else {
                echo 'BulkStudentImport.php file does not exist!<br>';
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() . '<br>';
        }
    }
}