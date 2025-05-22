<?php
// BulkImport controller for importing students with class and section in CSV
// URL: /bulkimport

defined('BASEPATH') or exit('No direct script access allowed');

class BulkImportCsv extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('download');
        $this->load->library('csvimport');
        $this->load->model('student_model');
        $this->load->model('classes_model');
        $this->load->model('section_model');
    }

    public function index()
    {
        if (!get_permission('student', 'is_add')) {
            access_denied();
        }
        $data = $this->data;
        $data['title'] = 'Bulk Student Import';
        $data['sub_page'] = 'student/bulk_import';
        $data['main_menu'] = 'multiple_import';
        $this->load->view('student/bulk_import_csv', $data);
    }
    // Removed upload() method; logic is now in Student::csv_import
}
