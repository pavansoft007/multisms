<?php defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Theme_settings extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('settings_model');
        $this->load->model('application_model');
        $this->load->library('form_validation');
    }

    /* theme settings controller */
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
        
        // Process form submission
        if ($_POST) {
            if ($this->input->post('submit') == 'theme') {
                $this->form_validation->set_rules('dark_skin', 'Dark Skin', 'trim|required');
                $this->form_validation->set_rules('border_mode', 'Border Mode', 'trim|required');
                
                if ($this->form_validation->run() !== false) {
                    $array = array(
                        'dark_skin' => $this->input->post('dark_skin'),
                        'border_mode' => $this->input->post('border_mode')
                    );
                    
                    $this->settings_model->update_theme_settings($array, $branchID);
                    
                    set_alert('success', translate('the_configuration_has_been_updated'));
                    redirect(current_url());
                }
            }
            
            if ($this->input->post('submit') == 'sidebar') {
                $this->form_validation->set_rules('sidebar_color', 'Sidebar Color', 'trim|required');
                
                if ($this->form_validation->run() !== false) {
                    $array = array(
                        'sidebar_color' => $this->input->post('sidebar_color'),
                        'sidebar_text_color' => $this->input->post('sidebar_text_color'),
                        'menu_text_color' => $this->input->post('menu_text_color'),
                        'menu_bg_color' => $this->input->post('menu_bg_color'),
                        'active_menu_text_color' => $this->input->post('active_menu_text_color'),
                        'active_menu_bg' => $this->input->post('active_menu_bg'),
                        'menu_hover_style' => $this->input->post('menu_hover_style')
                    );
                    
                    $this->settings_model->update_theme_settings($array, $branchID);
                    
                    set_alert('success', translate('the_configuration_has_been_updated'));
                    redirect(current_url() . '#sidebar');
                }
            }
        }
        
        $this->data['title'] = 'Theme Settings';
        $this->data['sub_page'] = 'theme_settings/index';
        $this->data['main_menu'] = 'settings';
        
        // Initialize global_images array to prevent PHP errors
        if (!isset($this->data['global_images']) || !is_array($this->data['global_images'])) {
            $this->data['global_images'] = array(
                'branch_id' => '',
                'text_logo' => 'logo-small.png'
            );
        }
        
        $this->load->view('layout/index', $this->data);
    }
}