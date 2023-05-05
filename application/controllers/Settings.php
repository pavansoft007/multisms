<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Bigwala Technologies school management system
 * @version : 2.0
 * @developed by : Bigwala Technologies
 * @support : Bigwala Technologies
 * @author url : https://bigwallatechnologies.com/
 * @filename : Accounting.php
 * @copyright : Reserved Bigwala Technologies Team
 */

class Settings extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        redirect(base_url(), 'refresh');
    }

    /* global settings controller */
    public function universal()
    {
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
            if (empty($config['reg_prefix'])) {
                $config['reg_prefix'] = false;
            }
            if($branchID){
                $branchID = $this->application_model->get_branch_id();
                $config['branch_id'] = $branchID;
                $select_global_settings = $this->db->select('id')->where(array(
                    'branch_id' => $branchID,
                ))->get('global_settings')->num_rows();
                if($select_global_settings == 1){
                    $this->db->where('branch_id', $branchID);
                    $this->db->update('global_settings', $config);
                    set_alert('success', translate('the_configuration_has_been_updated'));
                    redirect(current_url());
                }else{
                    $this->db->insert('global_settings', $config);
                }
            }else{
                $this->db->where('id', 1);
                $this->db->update('global_settings', $config);
                set_alert('success', translate('the_configuration_has_been_updated'));
                redirect(current_url());
            }
        }

        if ($this->input->post('submit') == 'theme') {
            foreach ($this->input->post() as $input => $value) {
                if ($input == 'submit') {
                    continue;
                }
                $config[$input] = $value;
            }

            if((!is_master_loggedin()) || (!is_superadmin_loggedin())){
                $branchID = $this->application_model->get_branch_id();
                $config['branch_id'] = $branchID;
                $select_theme_settings = $this->db->select('id')->where(array(
                    'branch_id' => $branchID,
                ))->get('theme_settings')->num_rows();
                if($select_theme_settings == 1){
                    $this->db->where('branch_id', $branchID);
                    $this->db->update('theme_settings', $config);
                    set_alert('success', translate('the_configuration_has_been_updated'));
                    $this->session->set_flashdata('active', 2);
                    redirect(current_url());
                }else{
                    $this->db->insert('theme_settings', $config);
                }
            }else{
                $this->db->where('id', 1);
                $this->db->update('theme_settings', $config);
                set_alert('success', translate('the_configuration_has_been_updated'));
                $this->session->set_flashdata('active', 2);
                redirect(current_url());
            }
        }

        if ($this->input->post('submit') == 'logo') {
            if((!is_master_loggedin()) || (!is_superadmin_loggedin())){
                $text_logo = 'logo-small.png'; 
                $old_text_logo = $this->input->post('old_text_logo');
                if ((isset($_FILES["text_logo"]) && !empty($_FILES['text_logo']['name']))) {
                    $config['upload_path'] = './uploads/app_image/text_logo';
                    $config['allowed_types'] = 'png';
                    $config['overwrite'] = FALSE;
                    $config['encrypt_name'] = TRUE;
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload("text_logo")) {
                        // need to unlink previous photo
                        if (!empty($old_text_logo)) {
                            $unlink_path = 'uploads/app_image/text_logo/';
                            if (file_exists($unlink_path . $old_text_logo)) {
                                @unlink($unlink_path . $old_text_logo);
                            }
                        }
                        $text_logo = $this->upload->data('file_name');
                    }
                }else{
                    if (!empty($old_text_logo)){
                        $text_logo = $old_text_logo;
                    }
                }
                $printing_logo = 'printing-logo.png';
                $old_printing_logo = $this->input->post('old_printing_logo');
                if ((isset($_FILES["print_file"]) && !empty($_FILES['print_file']['name']))) {
                    $config['upload_path'] = './uploads/app_image/printing_logo';
                    $config['allowed_types'] = 'png';
                    $config['overwrite'] = FALSE;
                    $config['encrypt_name'] = TRUE;
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload("print_file")) {
                        // need to unlink previous photo
                        if (!empty($old_printing_logo)) {
                            $unlink_path = 'uploads/app_image/printing_logo/';
                            if (file_exists($unlink_path . $old_printing_logo)) {
                                @unlink($unlink_path . $old_printing_logo);
                            }
                        }
                        $printing_logo = $this->upload->data('file_name');
                    }
                }else{
                    if (!empty($old_printing_logo)){
                        $printing_logo = $old_printing_logo;
                    }
                }
                $report_logo = 'report-card-logo.png';
                $old_report_logo = $this->input->post('old_report_logo');
                if ((isset($_FILES["report_card"]) && !empty($_FILES['report_card']['name']))) {
                    $config['upload_path'] = './uploads/app_image/report_logo';
                    $config['allowed_types'] = 'png';
                    $config['overwrite'] = FALSE;
                    $config['encrypt_name'] = TRUE;
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload("report_card")) {
                        // need to unlink previous photo
                        if (!empty($old_report_logo)) {
                            $unlink_path = 'uploads/app_image/report_logo/';
                            if (file_exists($unlink_path . $old_report_logo)) {
                                @unlink($unlink_path . $old_report_logo);
                            }
                        }
                        $report_logo = $this->upload->data('file_name');
                    }
                }else{
                    if (!empty($old_report_logo)){
                        $report_logo = $old_report_logo;
                    }
                }
                $branchID = $this->application_model->get_branch_id();
                $global_images['branch_id'] = $branchID;
                $global_images['text_logo'] = $text_logo;
                $global_images['printing_logo'] = $printing_logo;
                $global_images['report_logo'] = $report_logo;
                $select_global_images = $this->db->select('id')->where(array('branch_id' => $branchID,))->get('global_images')->num_rows();
                if($select_global_images == 1){
                    $this->db->where('branch_id', $branchID);
                    $this->db->update('global_images', $global_images);
                    set_alert('success', translate('the_configuration_has_been_updated'));
                    $this->session->set_flashdata('active', 3);
                    redirect(current_url());
                }else{
                    $this->db->insert('global_images', $global_images);
                }

            }
            set_alert('success', translate('the_configuration_has_been_updated'));
            $this->session->set_flashdata('active', 3);
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
        $this->data['title'] = translate('school_settings');
        $this->data['sub_page'] = 'settings/school';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    public function unique_branchname($name)
    {
        $this->db->where_not_in('id', get_loggedin_branch_id());
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
        $this->form_validation->set_rules('paypal_username', 'Paypal Username', 'trim|required');
        $this->form_validation->set_rules('paypal_password', 'Paypal Password', 'trim|required');
        $this->form_validation->set_rules('paypal_signature', 'Paypal Signature', 'trim|required');
        $this->form_validation->set_rules('paypal_email', 'Paypal Email', 'trim|required');
        if ($this->form_validation->run() !== false) {
            $paypal_sandbox = isset($_POST['paypal_sandbox']) ? 1 : 2;
            $arrayPaypal = array(
                'paypal_username' => $this->input->post('paypal_username'),
                'paypal_password' => $this->input->post('paypal_password'),
                'paypal_signature' => $this->input->post('paypal_signature'),
                'paypal_email' => $this->input->post('paypal_email'),
                'paypal_sandbox' => $paypal_sandbox,
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayPaypal['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayPaypal);
            } else {
                $this->db->where('id', $q->row()->id);
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
        $this->form_validation->set_rules('stripe_secret', 'Stripe Secret Key', 'trim|required');
        if ($this->form_validation->run() !== false) {
            $stripe_demo = isset($_POST['stripe_demo']) ? 1 : 2;
            $arrayPaypal = array(
                'stripe_secret' => $this->input->post('stripe_secret'),
                'stripe_demo' => $stripe_demo,
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayPaypal['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayPaypal);
            } else {
                $this->db->where('id', $q->row()->id);
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

    public function payumoney_save()
    {
        if (!get_permission('payment_settings', 'is_add')) {
            ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->form_validation->set_rules('payumoney_key', 'Payumoney Key', 'trim|required');
        $this->form_validation->set_rules('payumoney_salt', 'Payumoney Salt', 'trim|required');
        if ($this->form_validation->run() !== false) {
            $payumoney_demo = isset($_POST['payumoney_demo']) ? 1 : 2;
            $arrayPayumoney = array(
                'payumoney_key' => $this->input->post('payumoney_key'),
                'payumoney_salt' => $this->input->post('payumoney_salt'),
                'payumoney_demo' => $payumoney_demo,
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayPayumoney['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayPayumoney);
            } else {
                $this->db->where('id', $q->row()->id);
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
        $this->form_validation->set_rules('paystack_secret_key', 'Paystack API Key', 'trim|required');
        if ($this->form_validation->run() !== false) {
            $arrayPaystack = array(
                'paystack_secret_key' => $this->input->post('paystack_secret_key'),
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayMollie['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayPaystack);
            } else {
                $this->db->where('id', $q->row()->id);
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
        $this->form_validation->set_rules('razorpay_key_id', 'Key Id', 'trim|required');
        $this->form_validation->set_rules('razorpay_key_secret', 'Key Secret', 'trim|required');
        if ($this->form_validation->run() !== false) {
            $razorpay_demo = isset($_POST['razorpay_demo']) ? 1 : 2;
            $arrayRazorpay = array(
                'razorpay_key_id' => $this->input->post('razorpay_key_id'),
                'razorpay_key_secret' => $this->input->post('razorpay_key_secret'),
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayRazorpay['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayRazorpay);
            } else {
                $this->db->where('id', $q->row()->id);
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
        $paypal_status = isset($_POST['paypal_status']) ? 1 : 2;
        $stripe_status = isset($_POST['stripe_status']) ? 1 : 2;
        $payumoney_status = isset($_POST['payumoney_status']) ? 1 : 2;
        $paystack_status = isset($_POST['paystack_status']) ? 1 : 2;
        $razorpay_status = isset($_POST['razorpay_status']) ? 1 : 2;
        $arrayData = array(
            'paypal_status' => $paypal_status,
            'stripe_status' => $stripe_status,
            'payumoney_status' => $payumoney_status,
            'paystack_status' => $paystack_status,
            'razorpay_status' => $razorpay_status,
        );
        
        $this->db->where('branch_id', $branchID);
        $q = $this->db->get('payment_config');
        if ($q->num_rows() == 0) {
            $arrayData['branch_id'] = $branchID;
            $this->db->insert('payment_config', $arrayData);
        } else {
            $this->db->where('id', $q->row()->id);
            $this->db->update('payment_config', $arrayData);
        }
        $message = translate('the_configuration_has_been_updated');
        $array = array('status' => 'success', 'message' => $message);
        echo json_encode($array);
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
}
