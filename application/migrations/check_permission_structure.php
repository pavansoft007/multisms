<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Check_permission_structure extends CI_Controller
{
    public function index()
    {
        $this->load->database();
        $fields = $this->db->list_fields('permission');
        echo "<pre>";
        print_r($fields);
        echo "</pre>";
    }
}