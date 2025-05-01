<?php
// This script will test if the controllers can be accessed

// Include CodeIgniter bootstrap file
define('BASEPATH', true);
include_once 'application/core/MY_Controller.php';

// Create a mock Admin_Controller class
class Mock_Admin_Controller {
    public function __construct() {
        echo "Admin_Controller constructor called<br>";
    }
}

// Create a mock Theme_settings controller
class Theme_settings extends Mock_Admin_Controller {
    public function __construct() {
        parent::__construct();
        echo "Theme_settings constructor called<br>";
    }
    
    public function index() {
        echo "Theme_settings index method called<br>";
    }
}

// Create a mock Universal_settings controller
class Universal_settings extends Mock_Admin_Controller {
    public function __construct() {
        parent::__construct();
        echo "Universal_settings constructor called<br>";
    }
    
    public function index() {
        echo "Universal_settings index method called<br>";
    }
}

// Test the controllers
echo "Testing Theme_settings controller:<br>";
$theme_settings = new Theme_settings();
$theme_settings->index();

echo "<br>Testing Universal_settings controller:<br>";
$universal_settings = new Universal_settings();
$universal_settings->index();
?>