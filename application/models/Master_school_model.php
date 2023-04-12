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
        if (!isset($data['branch_id'])) {
            $this->db->insert('branch', $arrayBranch);
            $branchID = $this->db->insert_id();
            //staff details
            $inser_data1 = array(
            'branch_id' => $branchID,
            'name' => $data['person_name'],
            'sex' => $data['sex'],
            'mobileno' => $data['mobileno'],
            'present_address' => $data['address'],
            'permanent_address' => $data['address'],
            'photo' => $this->uploadImage('staff'),
            'joining_date' => date("Y-m-d", strtotime($data['joining_date'])),
            'email' => $data['email']
            );
            // RANDOM STAFF ID GENERATE
            $inser_data1['staff_id'] = substr(app_generate_hash(), 3, 7);
            // SAVE EMPLOYEE INFORMATION IN THE DATABASE
            $sql = $this->db->insert('staff', $inser_data1);
            $employeeID = $this->db->insert_id();
            // user account
            $inser_data2['active'] = 1;
            $inser_data2['role'] = 9;
            $inser_data2['user_id'] = $employeeID;
            $inser_data2['username'] = $data['email'];
            $inser_data2['password'] = $this->app_lib->pass_hashed($data["password"]);
            $this->db->insert('login_credential', $inser_data2);
        } else {
            $this->db->where('id', $data['branch_id']);
            $this->db->update('branch', $arrayBranch);
            //person details
            $this->db->where('id', $data['staff_id']);
            $this->db->update('staff', $inser_data1);
            // user account
            $this->db->where('username', $data['email']);
            $this->db->update('login_credential', $inser_data2);
        }

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
