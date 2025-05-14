<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Master_school_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function save($data)
    {
        // Validate required fields
        $requiredFields = ['branch_name', 'school_name', 'email', 'mobileno', 'currency', 'currency_symbol', 'joining_date', 'role_id', 'password'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                error_log("Missing required field: $field");
                return false;
            }
        }

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
            'joining_date' => date("Y-m-d", strtotime($data['joining_date'])),
            'contact_person_gender' => $data['contact_person_gender'],
            'contact_person_name' => $data['contact_person_name'],
        );

        if (!isset($data['branch_id'])) {
            if (!$this->db->insert('branch', $arrayBranch)) {
                error_log("Database insert error: " . $this->db->error()['message']);
                return false;
            }
            $branchID = $this->db->insert_id();

            $inser_data2 = array(
                'active' => 1,
                'role' => $data['role_id'],
                'user_id' => $branchID,
                'username' => $data['email'],
                'password' => $this->app_lib->pass_hashed($data["password"]),
            );

            if (!$this->db->insert('login_credential', $inser_data2)) {
                error_log("Database insert error for login_credential: " . $this->db->error()['message']);
                return false;
            }
        } else {
            $this->db->where('id', $data['branch_id']);
            if (!$this->db->update('branch', $arrayBranch)) {
                error_log("Database update error: " . $this->db->error()['message']);
                return false;
            }

            $this->db->where('username', $data['email']);
            if (!$this->db->update('login_credential', array('role' => $data['role_id']))) {
                error_log("Database update error for login_credential: " . $this->db->error()['message']);
                return false;
            }
        }

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            error_log("No rows affected during save operation.");
            return false;
        }
    }
}
