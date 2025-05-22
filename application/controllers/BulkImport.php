<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BulkImport extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Simple output to test if the controller works
        echo "BulkImport controller is working!";
    }
}