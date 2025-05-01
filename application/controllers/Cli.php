<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cli extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function check_tables()
    {
        // Check if running from CLI
        if (!is_cli()) {
            echo "This script can only be run from the command line.";
            return;
        }

        // Check if roles table exists
        $roles_exists = $this->db->table_exists('roles');
        echo "Roles table exists: " . ($roles_exists ? "Yes" : "No") . "\n";

        // Check if footer_menu_config table exists
        $footer_menu_exists = $this->db->table_exists('footer_menu_config');
        echo "Footer menu config table exists: " . ($footer_menu_exists ? "Yes" : "No") . "\n";

        // If roles table exists, check the content
        if ($roles_exists) {
            $roles = $this->db->get('roles')->result_array();
            echo "Number of roles: " . count($roles) . "\n";
            echo "Roles:\n";
            foreach ($roles as $role) {
                echo "- ID: " . $role['id'] . ", Name: " . $role['name'] . "\n";
            }
        }

        // If footer_menu_config table exists, check the content
        if ($footer_menu_exists) {
            $footer_menu = $this->db->get('footer_menu_config')->result_array();
            echo "Number of footer menu items: " . count($footer_menu) . "\n";
            echo "Footer menu items:\n";
            foreach ($footer_menu as $item) {
                echo "- Role ID: " . $item['role_id'] . ", Menu Item: " . $item['menu_item'] . ", Status: " . $item['status'] . "\n";
            }
        }
    }
}