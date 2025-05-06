<?php defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Universal_settings extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('settings_model');
        $this->load->model('email_model');
        $this->load->model('sms_model');
        $this->load->model('application_model');
        $this->load->library('form_validation');
    }

    /* global settings controller */
    public function index() {
        // Set default theme config to prevent errors
        $this->data['theme_config'] = array(
            'dark_skin' => 'false',
            'border_mode' => 'true',
            'sidebar_color' => 'default',
            'sidebar_text_color' => 'light',
            'menu_text_color' => 'light',
            'menu_bg_color' => 'default',
            'active_menu_text_color' => 'light',
            'active_menu_bg' => 'default',
            'menu_hover_style' => 'default'
        );
        
        // Get global configuration
        $branchID = 0;
        if (isset($this->application_model) && method_exists($this->application_model, 'get_branch_id')) {
            $branchID = $this->application_model->get_branch_id();
        }
        
        $this->data['global_config'] = $this->db->where('branch_id', $branchID)->get('global_settings')->row_array();
        if (empty($this->data['global_config'])) {
            $this->data['global_config'] = array(
                'animations' => 'fadeIn'
            );
        }
        
        // Get theme settings
        $theme_settings = $this->db->where('branch_id', $branchID)->get('theme_settings');
        if ($theme_settings->num_rows() > 0) {
            $this->data['theme_config'] = array_merge($this->data['theme_config'], $theme_settings->row_array());
        }
        
        // Get global images
        $this->data['global_images'] = $this->db->where('branch_id', '0')->get('global_images')->row_array();
        if (empty($this->data['global_images'])) {
            $this->data['global_images'] = array(
                'branch_id' => '',
                'system_logo' => 'logo.png',
                'text_logo' => 'logo-small.png',
                'printing_logo' => 'printing-logo.png',
                'report_logo' => 'report-card-logo.png'
            );
        }
        
        $this->data['title'] = 'Universal Settings';
        $this->data['sub_page'] = 'universal_settings/index';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }
}