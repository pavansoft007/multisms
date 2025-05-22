<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SimpleImport extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo "SimpleImport controller is working!";
    }
}