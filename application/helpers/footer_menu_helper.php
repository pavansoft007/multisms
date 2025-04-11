<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper function to get footer menu items for a specific role
 * 
 * @param int $role_id The role ID
 * @return array Array of menu items
 */
if (!function_exists('get_footer_menu_items')) {
    function get_footer_menu_items($role_id) {
        $CI =& get_instance();
        
        // Debug the role ID
        error_log('Getting footer menu items for role ID: ' . $role_id);
        
        // Get footer menu items for this role - use direct query for debugging
        $query = "SELECT * FROM footer_menu_config WHERE role_id = ? AND status = 1";
        $result = $CI->db->query($query, array($role_id));
        $footer_items = $result->result_array();
        
        // Debug the query
        error_log('SQL Query: ' . $CI->db->last_query());
        error_log('Number of items found: ' . count($footer_items));
        
        $menu_items = array();
        if (!empty($footer_items)) {
            foreach ($footer_items as $item) {
                $menu_items[] = $item['menu_item'];
            }
            
            // Log for debugging
            error_log('Footer menu items for role ' . $role_id . ': ' . json_encode($menu_items));
        } else {
            error_log('No footer menu items found for role ' . $role_id . ' - using defaults');
            
            // Default menu items
            $menu_items = array('dashboard');
            
            if ($role_id == 7 || ($role_id == 6 && function_exists('get_activeChildren_id') && !empty(get_activeChildren_id()))) {
                $menu_items[] = 'homework';
                $menu_items[] = 'attendance';
                $menu_items[] = 'fees';
            } else {
                $menu_items[] = 'students';
                $menu_items[] = 'payments';
                $menu_items[] = 'attendance';
            }
            
            $menu_items[] = 'message';
        }
        
        return $menu_items;
    }
}