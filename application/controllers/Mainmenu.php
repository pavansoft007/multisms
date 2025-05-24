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
        $this->load->model('parents_model'); // Ensure parents_model is loaded for parent logic
        
        // Enable error reporting for debugging
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    public function index()
    {
        $CI =& get_instance();
        // Check if the user is logged in
        if (!$CI->session->userdata('loggedin')) {
            redirect(base_url('authentication'));
        }
        $data = [];
        // Get user role ID
        $role_id = $CI->session->userdata('loggedin_role_id');
        // Get all modules
        try {
            $data['modules'] = $CI->role_model->getModulesList();
        } catch (Exception $e) {
            $data['modules'] = array();
            log_message('error', 'Error getting modules list: ' . $e->getMessage());
        }
        // Get user permissions
        try {
            $permissions = get_staff_permissions($role_id);
            $data['permissions'] = $permissions;
        } catch (Exception $e) {
            $data['permissions'] = array();
            log_message('error', 'Error getting permissions: ' . $e->getMessage());
        }
        // Get all menu items from sidebar
        $data['menu_items'] = get_main_menu_items($role_id);
        // Get user info
        $data['user_name'] = $CI->session->userdata('name');
        $data['user_role'] = loggedin_role_name();
        // Ensure $theme_config is initialized before passing to views
        $data['theme_config'] = isset($data['theme_config']) ? $data['theme_config'] : [];
        $data['theme_config']['border_mode'] = isset($data['theme_config']['border_mode']) ? $data['theme_config']['border_mode'] : 'true';
        $data['title'] = translate('main_menu');
        $data['main_menu'] = 'dashboard';
        $data['sub_page'] = 'main_menu_web';
        // --- Parent child selection logic ---
        if ($role_id == 6 && is_parent_loggedin()) {
            $parent_id = get_loggedin_user_id();
            $children = $CI->parents_model->childsResult($parent_id);
            $selected_child_id = $CI->session->userdata('myChildren_id');
            $data['children'] = $children;
            $data['selected_child_id'] = $selected_child_id;
        }
        // Check if the request is coming from a mobile device
        $is_mobile = false;
        if ($CI->input->cookie('is_mobile') === 'true') {
            $is_mobile = true;
        } else {
            $screen_width = $CI->input->cookie('screen_width');
            if ($screen_width !== false && intval($screen_width) <= 767) {
                $is_mobile = true;
            } else {
                $user_agent = $CI->input->server('HTTP_USER_AGENT');
                $mobile_agents = array(
                    'Android', 'webOS', 'iPhone', 'iPad', 'iPod', 'BlackBerry', 'Windows Phone',
                    'Mobile', 'Opera Mini', 'Opera Mobi', 'IEMobile', 'Silk', 'Kindle'
                );
                foreach ($mobile_agents as $agent) {
                    if (stripos($user_agent, $agent) !== false) {
                        $is_mobile = true;
                        break;
                    }
                }
            }
        }
        $data['is_mobile'] = $is_mobile;
        // Load the appropriate view based on device type
        if ($is_mobile) {
            $CI->load->view('main_menu', $data);
        } else {
            $CI->load->view('layout/index', $data);
        }
    }
}