<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Simple extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        // Load a simple view for testing
        $this->load->view('simple_view');
    }
}<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Simple extends Admin_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        // Load a simple view for testing
        $this->load->view('simple_view');
    }
}<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Simple extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        echo 'Hello World';
    }
}