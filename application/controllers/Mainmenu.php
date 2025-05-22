<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Bigwala Technologies school management system
 * @version : 1.0
 * @developed by : Bigwala Technologies
 * @support : bigwalatechnologies@bigwallatechnologies.com
 * @author url : https://bigwallatechnologies.com
 * @filename : Mainmenu.php
 * @copyright : Reserved Bigwala Technologiess Team
 */

class Mainmenu extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data = []; // Initialize $data property
        $this->load->library('session'); // Load session library
        $this->load->model('role_model'); // Load role_model
        
        // Enable error reporting for debugging
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    public function index()
    {
        // Check if the user is logged in
        if (!is_loggedin()) {
            redirect(base_url('authentication'));
        }

        // Get user role ID
        $role_id = $this->session->userdata('loggedin_role_id');
        
        // Get all modules
        try {
            $this->data['modules'] = $this->role_model->getModulesList();
        } catch (Exception $e) {
            $this->data['modules'] = array();
            log_message('error', 'Error getting modules list: ' . $e->getMessage());
        }
        
        // Get user permissions
        try {
            $permissions = get_staff_permissions($role_id);
            $this->data['permissions'] = $permissions;
        } catch (Exception $e) {
            $this->data['permissions'] = array();
            log_message('error', 'Error getting permissions: ' . $e->getMessage());
        }
        
        // Get all menu items from sidebar
        $this->data['menu_items'] = get_main_menu_items($role_id);
        
        // Get all menu items from sidebar
        $this->data['menu_items'] = get_main_menu_items($role_id);
        
        // Get user info
        $this->data['user_name'] = $this->session->userdata('name');
        $this->data['user_role'] = loggedin_role_name();
        
        // Ensure $theme_config is initialized before passing to views
        $this->data['theme_config'] = isset($this->data['theme_config']) ? $this->data['theme_config'] : [];
        
        // Ensure 'border_mode' is set in $theme_config
        $this->data['theme_config']['border_mode'] = isset($this->data['theme_config']['border_mode']) ? $this->data['theme_config']['border_mode'] : 'true';
        
        // Set page title
        $this->data['title'] = translate('main_menu');
        $this->data['main_menu'] = 'dashboard';
        $this->data['sub_page'] = 'main_menu_web';
        
        // Check if the request is coming from a mobile device
        $is_mobile = $this->is_mobile_device();
        $this->data['is_mobile'] = $is_mobile;
        
        // Load the appropriate view based on device type
        if ($is_mobile) {
            // For mobile devices, use the standalone view with footer menu
            $this->load->view('main_menu', $this->data);
        } else {
            // For desktop views, use the layout with the main menu web view and sidebar
            $this->load->view('layout/index', $this->data);
        }
    }
    
    // Function to detect if the request is coming from a mobile device
    private function is_mobile_device() {
        // First check the cookie set by JavaScript (most reliable)
        if ($this->input->cookie('is_mobile') === 'true') {
            return true;
        }
        
        // Check screen width from cookie
        $screen_width = $this->input->cookie('screen_width');
        if ($screen_width !== false && intval($screen_width) <= 767) {
            return true;
        }
        
        // Then check user agent
        $user_agent = $this->input->server('HTTP_USER_AGENT');
        $mobile_agents = array(
            'Android', 'webOS', 'iPhone', 'iPad', 'iPod', 'BlackBerry', 'Windows Phone',
            'Mobile', 'Opera Mini', 'Opera Mobi', 'IEMobile', 'Silk', 'Kindle'
        );
        
        foreach ($mobile_agents as $agent) {
            if (stripos($user_agent, $agent) !== false) {
                return true;
            }
        }
        
        return false;
    }
}