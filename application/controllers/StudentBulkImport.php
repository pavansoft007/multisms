<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StudentBulkImport extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Simple output to test if the controller works
        echo "StudentBulkImport controller is working!";
        
        // Load the view
        $this->data['title'] = 'Student Bulk Import';
        $this->data['sub_page'] = 'bulkstudentimport/index';
        $this->data['main_menu'] = 'admission';
        $this->load->view('layout/index', $this->data);
    }
}