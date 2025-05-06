<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Get global settings
    public function get_global_settings($branch_id = 0)
    {
        $this->db->where('branch_id', $branch_id);
        return $this->db->get('global_settings')->row_array();
    }

    // Get theme settings
    public function get_theme_settings($branch_id = 0)
    {
        $this->db->where('branch_id', $branch_id);
        return $this->db->get('theme_settings')->row_array();
    }

    // Update global settings
    public function update_global_settings($data, $branch_id = 0)
    {
        $this->db->where('branch_id', $branch_id);
        $query = $this->db->get('global_settings');
        if ($query->num_rows() > 0) {
            $this->db->where('branch_id', $branch_id);
            $this->db->update('global_settings', $data);
        } else {
            $data['branch_id'] = $branch_id;
            $this->db->insert('global_settings', $data);
        }
        return true;
    }

    // Update theme settings
    public function update_theme_settings($data, $branch_id = 0)
    {
        $this->db->where('branch_id', $branch_id);
        $query = $this->db->get('theme_settings');
        if ($query->num_rows() > 0) {
            $this->db->where('branch_id', $branch_id);
            $this->db->update('theme_settings', $data);
        } else {
            $data['branch_id'] = $branch_id;
            $this->db->insert('theme_settings', $data);
        }
        return true;
    }
}