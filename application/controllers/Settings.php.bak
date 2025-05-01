<?php defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );

class Settings extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('settings_model');
        $this->load->model('email_model');
        $this->load->model('sms_model');
        $this->load->library('form_validation');
    }

    /* global settings controller */
    public function universal() {
        if (!get_permission('global_settings', 'is_view')) {
            access_denied();
        }

        if ($_POST) {
            if (!get_permission('global_settings', 'is_edit')) {
                access_denied();
            }
        }

        $config = array();
        if ($this->input->post('submit') == 'setting') {
            foreach ($this->input->post() as $input => $value) {
                if ($input == 'submit') {
                    continue;
                }
                $config[$input] = $value;
            }
            if((!is_master_loggedin()) && (!is_superadmin_loggedin())){
                $branchID = $this->application_model->get_branch_id();
                $config['branch_id'] = $branchID;
                $select_global_settings = $this->db->select('id')->where(array(
                    'branch_id' => $branchID,
                ))->get('global_settings')->num_rows();
                if($select_global_settings == 1){
                    $this->db->where('branch_id', $branchID);
                    $this->db->update('global_settings', $config);
                }else{
                    $this->db->insert('global_settings', $config);
                }
            }else{
                $config['branch_id'] = '0';
                $select_global_settings = $this->db->select('id')->where(array(
                    'branch_id' => '0',
                ))->get('global_settings')->num_rows();
                if($select_global_settings == 1){
                    $this->db->where('branch_id', '0');
                    $this->db->update('global_settings', $config);
                }else{
                    $this->db->insert('global_settings', $config);
                }
            }
            set_alert('success', translate('the_configuration_has_been_updated'));
            redirect(current_url());
        }

        if ($this->input->post('submit') == 'theme') {
            foreach ($this->input->post() as $input => $value) {
                if ($input == 'submit') {
                    continue;
                }
                $config[$input] = $value;
            }
            if((!is_master_loggedin()) && (!is_superadmin_loggedin())){
                $branchID = $this->application_model->get_branch_id();
                $config['branch_id'] = $branchID;
                $select_theme_settings = $this->db->select('id')->where(array(
                    'branch_id' => $branchID,
                ))->get('theme_settings')->num_rows();
                if($select_theme_settings == 1){
                    $this->db->where('branch_id', $branchID);
                    $this->db->update('theme_settings', $config);
                }else{
                    $this->db->insert('theme_settings', $config);
                }
            }else{
                $config['branch_id'] = '0';
                $select_theme_settings = $this->db->select('id')->where(array(
                    'branch_id' => '0',
                ))->get('theme_settings')->num_rows();
                if($select_theme_settings == 1){
                    $this->db->where('branch_id', '0');
                    $this->db->update('theme_settings', $config);
                }else{
                    $this->db->insert('theme_settings', $config);
                }
            }
            set_alert('success', translate('the_configuration_has_been_updated'));
            redirect(current_url());
        }

        if ($this->input->post('submit') == 'logo') {
            move_uploaded_file($_FILES['logo_file']['tmp_name'], 'uploads/app_image/logo.png');
            move_uploaded_file($_FILES['text_logo']['tmp_name'], 'uploads/app_image/logo-small.png');
            move_uploaded_file($_FILES['print_file']['tmp_name'], 'uploads/app_image/printing-logo.png');
            move_uploaded_file($_FILES['report_card']['tmp_name'], 'uploads/app_image/report-card-logo.png');

            set_alert('success', translate('the_configuration_has_been_updated'));
            redirect(current_url());
        }

        $this->data['title'] = translate('global_settings');
        $this->data['sub_page'] = 'settings/universal';
        $this->data['main_menu'] = 'settings';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    /* sidebar settings controller */
    public function sidebar()
    {
        if (!get_permission('global_settings', 'is_view')) {
            access_denied();
        }

        if ($_POST) {
            if (!get_permission('global_settings', 'is_edit')) {
                access_denied();
            }
            
            $config = array();
            foreach ($this->input->post() as $input => $value) {
                if ($input == 'submit') {
                    continue;
                }
                
                // Validate sidebar color value
                if ($input == 'sidebar_color' && !in_array($value, ['default', 'blue', 'green', 'purple', 'red', 'dark'])) {
                    set_alert('error', 'Invalid sidebar color selection');
                    redirect(current_url());
                }
                
                // Validate sidebar text color value
                if ($input == 'sidebar_text_color' && !in_array($value, ['light', 'dark'])) {
                    set_alert('error', 'Invalid sidebar text color selection');
                    redirect(current_url());
                }
                
                // Validate menu text color value
                if ($input == 'menu_text_color' && !in_array($value, ['light', 'dark'])) {
                    set_alert('error', 'Invalid menu text color selection');
                    redirect(current_url());
                }
                
                // Validate menu background color value
                if ($input == 'menu_bg_color' && !in_array($value, ['default', 'light', 'dark'])) {
                    set_alert('error', 'Invalid menu background color selection');
                    redirect(current_url());
                }
                
                // Validate active menu text color value
                if ($input == 'active_menu_text_color' && !in_array($value, ['light', 'dark', 'primary'])) {
                    set_alert('error', 'Invalid active menu text color selection');
                    redirect(current_url());
                }
                
                // Validate active menu background value
                if ($input == 'active_menu_bg' && !in_array($value, ['default', 'light', 'primary', 'dark'])) {
                    set_alert('error', 'Invalid active menu background selection');
                    redirect(current_url());
                }
                
                // Validate menu hover style value
                if ($input == 'menu_hover_style' && !in_array($value, ['default', 'light', 'dark', 'primary'])) {
                    set_alert('error', 'Invalid menu hover style selection');
                    redirect(current_url());
                }
                
                $config[$input] = $value;
            }

            if((!is_master_loggedin()) && (!is_superadmin_loggedin())){
                $branchID = $this->application_model->get_branch_id();
                $config['branch_id'] = $branchID;
                $select_theme_settings = $this->db->select('id')->where(array(
                    'branch_id' => $branchID,
                ))->get('theme_settings')->num_rows();
                if($select_theme_settings == 1){
                    $this->db->where('branch_id', $branchID);
                    if ($this->db->update('theme_settings', $config)) {
                        // Clear all caches
                        $this->session->unset_userdata('theme_config');
                        $this->cache->delete('theme_config');
                        set_alert('success', translate('the_configuration_has_been_updated'));
                        redirect(current_url());
                    } else {
                        set_alert('error', 'Failed to update settings');
                        redirect(current_url());
                    }
                }else{
                    if ($this->db->insert('theme_settings', $config)) {
                        // Clear all caches
                        $this->session->unset_userdata('theme_config');
                        $this->cache->delete('theme_config');
                        set_alert('success', translate('the_configuration_has_been_updated'));
                        redirect(current_url());
                    } else {
                        set_alert('error', 'Failed to update settings');
                        redirect(current_url());
                    }
                }
            }else{
                $config['branch_id'] = '0';
                $select_theme_settings = $this->db->select('id')->where(array(
                    'branch_id' => '0',
                ))->get('theme_settings')->num_rows();
                if($select_theme_settings == 1){
                    $this->db->where('branch_id', '0');
                    if ($this->db->update('theme_settings', $config)) {
                        // Clear all caches
                        $this->session->unset_userdata('theme_config');
                        $this->cache->delete('theme_config');
                        set_alert('success', translate('the_configuration_has_been_updated'));
                        redirect(current_url());
                    } else {
                        set_alert('error', 'Failed to update settings');
                        redirect(current_url());
                    }
                }else{
                    if ($this->db->insert('theme_settings', $config)) {
                        // Clear all caches
                        $this->session->unset_userdata('theme_config');
                        $this->cache->delete('theme_config');
                        set_alert('success', translate('the_configuration_has_been_updated'));
                        redirect(current_url());
                    } else {
                        set_alert('error', 'Failed to update settings');
                        redirect(current_url());
                    }
                }
            }
        }

        $this->data['title'] = translate('sidebar_settings');
        $this->data['sub_page'] = 'settings/sidebar_settings_complete';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    /* school settings controller */
    public function school()
    {
        if (!get_permission('school_settings', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (!get_permission('school_settings', 'is_edit')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('branch_name', translate('branch_name'), 'trim|required|callback_unique_branchname');
            $this->form_validation->set_rules('school_name', translate('school_name'), 'trim|required');
            $this->form_validation->set_rules('email', translate('email'), 'trim|required|valid_email');
            $this->form_validation->set_rules('currency', translate('currency'), 'trim|required');
            if ($this->form_validation->run() == true) {
                $this->branchUpdate($this->input->post());
                $message = translate('the_configuration_has_been_updated');
                $array = array('status' => 'success', 'message' => $message);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        $this->data['branch'] = $this->settings_model->getBranchDetails();
        $this->data['title'] = translate('school_settings');
        $this->data['sub_page'] = 'settings/school';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    public function unique_branchname($name)
    {
        $branch_id = $this->application_model->get_branch_id();
        $this->db->where_not_in('id', $branch_id);
        $this->db->where('name', $name);
        $name = $this->db->get('branch')->num_rows();
        if ($name == 0) {
            return true;
        } else {
            $this->form_validation->set_message("unique_branchname", translate('already_taken'));
            return false;
        }
    }

    public function payment()
    {
        if (!get_permission('payment_settings', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $this->data['branch_id'] = $branchID;
        $this->data['config'] = $this->get_payment_config();
        $this->data['sub_page'] = 'settings/payment_gateway';
        $this->data['main_menu'] = 'settings';
        $this->data['title'] = translate('payment_control');
        $this->load->view('layout/index', $this->data);
    }

    public function paypal_save()
    {
        if (!get_permission('payment_settings', 'is_add')) {
            ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->form_validation->set_rules('paypal_username', translate('username'), 'trim|required');
        $this->form_validation->set_rules('paypal_password', translate('password'), 'trim|required');
        $this->form_validation->set_rules('paypal_signature', translate('signature'), 'trim|required');
        $this->form_validation->set_rules('paypal_email', translate('paypal_email'), 'trim|required');
        if ($this->form_validation->run() !== false) {
            $arrayPaypal = array(
                'paypal_username' => $this->input->post('paypal_username'),
                'paypal_password' => $this->input->post('paypal_password'),
                'paypal_signature' => $this->input->post('paypal_signature'),
                'paypal_email' => $this->input->post('paypal_email'),
                'paypal_sandbox' => (isset($_POST['paypal_sandbox']) ? 1 : 0),
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayPaypal['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayPaypal);
            } else {
                $this->db->where('branch_id', $branchID);
                $this->db->update('payment_config', $arrayPaypal);
            }
            $message = translate('the_configuration_has_been_updated');
            $array = array('status' => 'success', 'message' => $message);
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function stripe_save()
    {
        if (!get_permission('payment_settings', 'is_add')) {
            ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->form_validation->set_rules('stripe_secret', translate('secret_key'), 'trim|required');
        if ($this->form_validation->run() !== false) {
            $stripe_secret = $this->input->post('stripe_secret');
            $arrayStripe = array(
                'stripe_secret' => $stripe_secret,
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayStripe['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayStripe);
            } else {
                $this->db->where('branch_id', $branchID);
                $this->db->update('payment_config', $arrayStripe);
            }
            $message = translate('the_configuration_has_been_updated');
            $array = array('status' => 'success', 'message' => $message);
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function payumoney_save()
    {
        if (!get_permission('payment_settings', 'is_add')) {
            ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->form_validation->set_rules('payumoney_key', translate('merchant_key'), 'trim|required');
        $this->form_validation->set_rules('payumoney_salt', translate('merchant_salt'), 'trim|required');
        if ($this->form_validation->run() !== false) {
            $payumoney_key = $this->input->post('payumoney_key');
            $payumoney_salt = $this->input->post('payumoney_salt');
            $arrayPayumoney = array(
                'payumoney_key' => $payumoney_key,
                'payumoney_salt' => $payumoney_salt,
                'payumoney_demo' => (isset($_POST['payumoney_demo']) ? 1 : 0),
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayPayumoney['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayPayumoney);
            } else {
                $this->db->where('branch_id', $branchID);
                $this->db->update('payment_config', $arrayPayumoney);
            }
            $message = translate('the_configuration_has_been_updated');
            $array = array('status' => 'success', 'message' => $message);
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function paystack_save()
    {
        if (!get_permission('payment_settings', 'is_add')) {
            ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->form_validation->set_rules('paystack_secret_key', translate('secret_key'), 'trim|required');
        if ($this->form_validation->run() !== false) {
            $paystack_secret_key = $this->input->post('paystack_secret_key');
            $arrayPaystack = array(
                'paystack_secret_key' => $paystack_secret_key,
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayPaystack['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayPaystack);
            } else {
                $this->db->where('branch_id', $branchID);
                $this->db->update('payment_config', $arrayPaystack);
            }
            $message = translate('the_configuration_has_been_updated');
            $array = array('status' => 'success', 'message' => $message);
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function razorpay_save()
    {
        if (!get_permission('payment_settings', 'is_add')) {
            ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->form_validation->set_rules('razorpay_key_id', translate('key_id'), 'trim|required');
        $this->form_validation->set_rules('razorpay_key_secret', translate('key_secret'), 'trim|required');
        if ($this->form_validation->run() !== false) {
            $razorpay_key_id = $this->input->post('razorpay_key_id');
            $razorpay_key_secret = $this->input->post('razorpay_key_secret');
            $arrayRazorpay = array(
                'razorpay_key_id' => $razorpay_key_id,
                'razorpay_key_secret' => $razorpay_key_secret,
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayRazorpay['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayRazorpay);
            } else {
                $this->db->where('branch_id', $branchID);
                $this->db->update('payment_config', $arrayRazorpay);
            }
            $message = translate('the_configuration_has_been_updated');
            $array = array('status' => 'success', 'message' => $message);
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function payment_active()
    {
        if (!get_permission('payment_settings', 'is_add')) {
            ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $paypal_status = isset($_POST['paypal_status']) ? 1 : 0;
        $stripe_status = isset($_POST['stripe_status']) ? 1 : 0;
        $payumoney_status = isset($_POST['payumoney_status']) ? 1 : 0;
        $paystack_status = isset($_POST['paystack_status']) ? 1 : 0;
        $razorpay_status = isset($_POST['razorpay_status']) ? 1 : 0;
        $midtrans_status = isset($_POST['midtrans_status']) ? 1 : 0;
        $sslcommerz_status = isset($_POST['sslcommerz_status']) ? 1 : 0;
        $jazzcash_status = isset($_POST['jazzcash_status']) ? 1 : 0;
        $flutterwave_status = isset($_POST['flutterwave_status']) ? 1 : 0;
        $arrayData = array(
            'paypal_status' => $paypal_status,
            'stripe_status' => $stripe_status,
            'payumoney_status' => $payumoney_status,
            'paystack_status' => $paystack_status,
            'razorpay_status' => $razorpay_status,
            'midtrans_status' => $midtrans_status,
            'sslcommerz_status' => $sslcommerz_status,
            'jazzcash_status' => $jazzcash_status,
            'flutterwave_status' => $flutterwave_status,
        );
        $this->db->where('branch_id', $branchID);
        $q = $this->db->get('payment_config');
        if ($q->num_rows() == 0) {
            $arrayData['branch_id'] = $branchID;
            $this->db->insert('payment_config', $arrayData);
        } else {
            $this->db->where('branch_id', $branchID);
            $this->db->update('payment_config', $arrayData);
        }
        $message = translate('the_configuration_has_been_updated');
        $array = array('status' => 'success', 'message' => $message);
        echo json_encode($array);
    }

    public function midtrans_save()
    {
        if (!get_permission('payment_settings', 'is_add')) {
            ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->form_validation->set_rules('midtrans_client_key', translate('client_key'), 'trim|required');
        $this->form_validation->set_rules('midtrans_server_key', translate('server_key'), 'trim|required');
        if ($this->form_validation->run() !== false) {
            $midtrans_client_key = $this->input->post('midtrans_client_key');
            $midtrans_server_key = $this->input->post('midtrans_server_key');
            $arrayMidtrans = array(
                'midtrans_client_key' => $midtrans_client_key,
                'midtrans_server_key' => $midtrans_server_key,
                'midtrans_sandbox' => (isset($_POST['midtrans_sandbox']) ? 1 : 0),
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayMidtrans['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayMidtrans);
            } else {
                $this->db->where('branch_id', $branchID);
                $this->db->update('payment_config', $arrayMidtrans);
            }
            $message = translate('the_configuration_has_been_updated');
            $array = array('status' => 'success', 'message' => $message);
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function sslcommerz_save()
    {
        if (!get_permission('payment_settings', 'is_add')) {
            ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->form_validation->set_rules('sslcz_store_id', translate('store_id'), 'trim|required');
        $this->form_validation->set_rules('sslcz_store_passwd', translate('store_password'), 'trim|required');
        if ($this->form_validation->run() !== false) {
            $sslcz_store_id = $this->input->post('sslcz_store_id');
            $sslcz_store_passwd = $this->input->post('sslcz_store_passwd');
            $arraySSLcommerz = array(
                'sslcz_store_id' => $sslcz_store_id,
                'sslcz_store_passwd' => $sslcz_store_passwd,
                'sslcommerz_sandbox' => (isset($_POST['sslcommerz_sandbox']) ? 1 : 0),
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arraySSLcommerz['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arraySSLcommerz);
            } else {
                $this->db->where('branch_id', $branchID);
                $this->db->update('payment_config', $arraySSLcommerz);
            }
            $message = translate('the_configuration_has_been_updated');
            $array = array('status' => 'success', 'message' => $message);
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function jazzcash_save()
    {
        if (!get_permission('payment_settings', 'is_add')) {
            ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->form_validation->set_rules('jazzcash_merchant_id', translate('merchant_id'), 'trim|required');
        $this->form_validation->set_rules('jazzcash_passwd', translate('password'), 'trim|required');
        $this->form_validation->set_rules('jazzcash_integerity_salt', translate('integerity_salt'), 'trim|required');
        if ($this->form_validation->run() !== false) {
            $arrayJazzcash = array(
                'jazzcash_merchant_id' => $this->input->post('jazzcash_merchant_id'),
                'jazzcash_passwd' => $this->input->post('jazzcash_passwd'),
                'jazzcash_integerity_salt' => $this->input->post('jazzcash_integerity_salt'),
                'jazzcash_sandbox' => (isset($_POST['jazzcash_sandbox']) ? 1 : 0),
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayJazzcash['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayJazzcash);
            } else {
                $this->db->where('branch_id', $branchID);
                $this->db->update('payment_config', $arrayJazzcash);
            }
            $message = translate('the_configuration_has_been_updated');
            $array = array('status' => 'success', 'message' => $message);
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function flutterwave_save()
    {
        if (!get_permission('payment_settings', 'is_add')) {
            ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->form_validation->set_rules('flutterwave_public_key', translate('public_key'), 'trim|required');
        $this->form_validation->set_rules('flutterwave_secret_key', translate('secret_key'), 'trim|required');
        if ($this->form_validation->run() !== false) {
            $arrayFlutterwave = array(
                'flutterwave_public_key' => $this->input->post('flutterwave_public_key'),
                'flutterwave_secret_key' => $this->input->post('flutterwave_secret_key'),
                'flutterwave_sandbox' => (isset($_POST['flutterwave_sandbox']) ? 1 : 0),
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayFlutterwave['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayFlutterwave);
            } else {
                $this->db->where('branch_id', $branchID);
                $this->db->update('payment_config', $arrayFlutterwave);
            }
            $message = translate('the_configuration_has_been_updated');
            $array = array('status' => 'success', 'message' => $message);
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    /* sms settings controller */
    public function smsconfig()
    {
        if (!get_permission('sms_settings', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (!get_permission('sms_settings', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('sms_service_provider', translate('sms_service_provider'), 'required');
            $this->form_validation->set_rules('sms_api_username', translate('username'), 'required');
            $this->form_validation->set_rules('sms_api_password', translate('password'), 'required');
            $this->form_validation->set_rules('sms_api_sender_id', translate('sender_id'), 'required');
            if ($this->form_validation->run() !== false) {
                $arrayConfig = array(
                    'sms_service_provider' => $this->input->post('sms_service_provider'),
                    'sms_api_username' => $this->input->post('sms_api_username'),
                    'sms_api_password' => $this->input->post('sms_api_password'),
                    'sms_api_sender_id' => $this->input->post('sms_api_sender_id'),
                );
                $this->db->where('id', 1);
                $this->db->update('sms_api', $arrayConfig);
                set_alert('success', translate('the_configuration_has_been_updated'));
                redirect(base_url('settings/smsconfig'));
            }
        }
        $this->data['api'] = $this->db->get_where('sms_api', array('id' => 1))->row_array();
        $this->data['title'] = translate('sms_config');
        $this->data['sub_page'] = 'settings/smsconfig';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    /* email settings controller */
    public function emailconfig()
    {
        if (!get_permission('email_settings', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (!get_permission('email_settings', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('email_protocol', translate('email_protocol'), 'required');
            $protocol = $this->input->post('email_protocol');
            if ($protocol == 'smtp') {
                $this->form_validation->set_rules('smtp_host', translate('smtp_host'), 'required');
                $this->form_validation->set_rules('smtp_user', translate('smtp_username'), 'required');
                $this->form_validation->set_rules('smtp_pass', translate('smtp_password'), 'required');
                $this->form_validation->set_rules('smtp_port', translate('smtp_port'), 'required');
                $this->form_validation->set_rules('smtp_encryption', translate('smtp_encryption'), 'required');
            }
            if ($this->form_validation->run() !== false) {
                $arrayConfig = array(
                    'protocol' => $this->input->post('email_protocol'),
                    'smtp_host' => $this->input->post('smtp_host'),
                    'smtp_user' => $this->input->post('smtp_user'),
                    'smtp_pass' => $this->input->post('smtp_pass'),
                    'smtp_port' => $this->input->post('smtp_port'),
                    'smtp_encryption' => $this->input->post('smtp_encryption'),
                    'mailtype' => 'html',
                    'newline' => "\r\n",
                );
                $this->db->where('id', 1);
                $this->db->update('email_config', $arrayConfig);
                set_alert('success', translate('the_configuration_has_been_updated'));
                redirect(base_url('settings/emailconfig'));
            }
        }
        $this->data['config'] = $this->db->get_where('email_config', array('id' => 1))->row_array();
        $this->data['title'] = translate('email_config');
        $this->data['sub_page'] = 'settings/emailconfig';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    public function branchUpdate($data)
    {
        $arrayBranch = array(
            'name' => $data['branch_name'],
            'school_name' => $data['school_name'],
            'email' => $data['email'],
            'mobileno' => $data['mobileno'],
            'currency' => $data['currency'],
            'symbol' => $data['currency_symbol'],
            'city' => $data['city'],
            'state' => $data['state'],
            'address' => $data['address'],
        );
        $this->db->where('id', get_loggedin_branch_id());
        $this->db->update('branch', $arrayBranch);
    }

    // transactions config
    public function transactions()
    {
        if (!get_permission('transaction_settings', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (!get_permission('transaction_settings', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('deposit_prefix', translate('deposit') . " " . translate('prefix'), 'trim|required');
            $this->form_validation->set_rules('expense_prefix', translate('expense') . " " . translate('prefix'), 'trim|required');
            if ($this->form_validation->run() !== false) {
                $branchID = $this->application_model->get_branch_id();
                $array = array(
                    'deposit_prefix' => $this->input->post('deposit_prefix'),
                    'expense_prefix' => $this->input->post('expense_prefix'),
                );
                $this->db->where('branch_id', $branchID);
                $query = $this->db->get('transactions_config');
                if ($query->num_rows() > 0) {
                    $this->db->where('branch_id', $branchID);
                    $this->db->update('transactions_config', $array);
                } else {
                    $array['branch_id'] = $branchID;
                    $this->db->insert('transactions_config', $array);
                }
                $message = translate('the_configuration_has_been_updated');
                $array = array('status' => 'success', 'message' => $message);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['config'] = $this->app_lib->get_transactions_config();
        $this->data['title'] = translate('transactions');
        $this->data['sub_page'] = 'settings/transactions';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    // mobile footer menu settings
    public function footer_menu()
    {
        if (!get_permission('footer_menu_settings', 'is_view')) {
            access_denied();
        }
        
        if ($_POST) {
            if (!get_permission('footer_menu_settings', 'is_add')) {
                access_denied();
            }
            
            $role_id = $this->input->post('role_id');
            $menu_items = $this->input->post('menu_items');
            
            // Validate role ID
            if (empty($role_id) || !in_array($role_id, [1, 2, 3, 6, 7])) {
                set_alert('error', 'Invalid role selected');
                redirect(current_url());
            }
            
            // Validate menu items
            if (empty($menu_items)) {
                $menu_items = [];
            }
            
            // Check if configuration already exists
            $this->db->where('role_id', $role_id);
            $query = $this->db->get('mobile_footer_menu');
            
            if ($query->num_rows() > 0) {
                // Update existing configuration
                $this->db->where('role_id', $role_id);
                $this->db->update('mobile_footer_menu', [
                    'menu_items' => json_encode($menu_items)
                ]);
            } else {
                // Insert new configuration
                $this->db->insert('mobile_footer_menu', [
                    'role_id' => $role_id,
                    'menu_items' => json_encode($menu_items)
                ]);
            }
            
            set_alert('success', translate('the_configuration_has_been_updated'));
            redirect(current_url());
        }
        
        $this->data['title'] = translate('footer_menu_settings');
        $this->data['sub_page'] = 'settings/footer_menu';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }
}