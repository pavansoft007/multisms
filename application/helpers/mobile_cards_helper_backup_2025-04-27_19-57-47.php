<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper function to get mobile cards for a specific role
 * 
 * @param int $role_id The role ID
 * @return array Array of card items
 */
if (!function_exists('get_mobile_cards')) {
    function get_mobile_cards($role_id) {
        $CI =& get_instance();
        
        // Log for debugging
        error_log("Getting mobile cards for role ID: " . $role_id);
        
        // Check if table exists to prevent errors
        if (!$CI->db->table_exists('mobile_cards_config')) {
            error_log("Table mobile_cards_config does not exist, returning empty array");
            return array(); // Return empty array instead of default cards
        }
        
        // IMPORTANT: Force integer type for role_id to ensure proper comparison
        $role_id = (int)$role_id;
        
        // Get from database directly - no caching for now
        $CI->db->select('card_item');
        $CI->db->from('mobile_cards_config');
        $CI->db->where('role_id', $role_id);
        $CI->db->where('status', 1);
        $CI->db->order_by('card_order', 'ASC');
        $query = $CI->db->get();
        
        error_log("SQL Query: " . $CI->db->last_query());
        
        // Debug output for development
        if (defined('ENVIRONMENT') && ENVIRONMENT !== 'production') {
            echo "<!-- DEBUG: SQL Query: " . $CI->db->last_query() . " -->\n";
        }
        
        $card_items = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $item) {
                $card_items[] = $item['card_item'];
            }
            
            error_log("Found " . count($card_items) . " cards in database for role " . $role_id . ": " . json_encode($card_items));
            
            // Debug output for development
            if (defined('ENVIRONMENT') && ENVIRONMENT !== 'production') {
                echo "<!-- DEBUG: Found " . count($card_items) . " cards in database for role " . $role_id . ": " . json_encode($card_items) . " -->\n";
            }
        } else {
            // Check if this role has ANY configuration (even disabled cards)
            $CI->db->where('role_id', $role_id);
            $check_query = $CI->db->get('mobile_cards_config');
            $has_config = ($check_query->num_rows() > 0);
            
            if ($has_config) {
                // This role has configuration but all cards are disabled
                error_log("Role " . $role_id . " has configuration but all cards are disabled");
                
                // Debug output for development
                if (defined('ENVIRONMENT') && ENVIRONMENT !== 'production') {
                    echo "<!-- DEBUG: Role " . $role_id . " has configuration but all cards are disabled -->\n";
                }
                
                $card_items = array(); // Empty array - no cards to show
            } else {
                error_log("No cards found in database for role " . $role_id . ", NOT using defaults");
                
                // Debug output for development
                if (defined('ENVIRONMENT') && ENVIRONMENT !== 'production') {
                    echo "<!-- DEBUG: No cards found in database for role " . $role_id . ", NOT using defaults -->\n";
                }
                
                // IMPORTANT: Do not use defaults - only show explicitly configured cards
                $card_items = array(); // Empty array - no cards to show
            }
        }
        
        return $card_items;
    }
    
    /**
     * Helper function to get default cards for a specific role
     * 
     * @param int $role_id The role ID
     * @return array Array of default card items
     */
    function get_default_cards($role_id) {
        // Default cards for all roles
        $default_cards = array('profile', 'settings');
        
        // Role-specific default cards
        if ($role_id == 7) { // Student
            $student_cards = array('attendance', 'fees', 'homework', 'marks', 'events');
            return array_merge($student_cards, $default_cards);
        } else if ($role_id == 6) { // Parent
            $parent_cards = array('attendance', 'fees', 'homework', 'marks', 'events');
            return array_merge($parent_cards, $default_cards);
        } else if ($role_id == 3) { // Teacher
            $teacher_cards = array('students', 'attendance', 'classes', 'homework', 'marks', 'events');
            return array_merge($teacher_cards, $default_cards);
        } else { // Admin and Superadmin
            $admin_cards = array('students', 'attendance', 'fees', 'classes', 'homework', 'marks', 'events', 'reports');
            return array_merge($admin_cards, $default_cards);
        }
    }
    
    /**
     * Helper function to get card details (icon, title, URL, color)
     * 
     * @param string $card_item The card item identifier
     * @param int $role_id The role ID (optional)
     * @return array Card details
     */
    function get_card_details($card_item, $role_id = 0) {
        // Default card details
        $card_details = array(
            'icon' => 'help',
            'title' => ucfirst($card_item),
            'url' => base_url($card_item),
            'color' => $card_item // Use the card item as the color class
        );
        
        switch ($card_item) {
            case 'students':
                $card_details = array(
                    'icon' => 'school',
                    'title' => 'Students',
                    'url' => base_url('student'),
                    'color' => 'students'
                );
                break;
            case 'attendance':
                $url = ($role_id == 7 || $role_id == 6) ? base_url('userrole/attendance') : base_url('attendance');
                $card_details = array(
                    'icon' => 'how_to_reg',
                    'title' => 'Attendance',
                    'url' => $url,
                    'color' => 'attendance'
                );
                break;
            case 'fees':
                $url = ($role_id == 7 || $role_id == 6) ? base_url('userrole/invoice') : base_url('fees');
                $card_details = array(
                    'icon' => 'attach_money',
                    'title' => 'Fees',
                    'url' => $url,
                    'color' => 'fees'
                );
                break;
            case 'classes':
                $card_details = array(
                    'icon' => 'class',
                    'title' => 'Classes',
                    'url' => base_url('classes'),
                    'color' => 'classes'
                );
                break;
            case 'homework':
                $url = ($role_id == 7 || $role_id == 6) ? base_url('userrole/homework') : base_url('homework');
                $card_details = array(
                    'icon' => 'assignment',
                    'title' => 'Homework',
                    'url' => $url,
                    'color' => 'homeworks' // Keep this for consistency with CSS
                );
                break;
            case 'marks':
                $url = ($role_id == 7 || $role_id == 6) ? base_url('userrole/exam') : base_url('exam');
                $card_details = array(
                    'icon' => 'grading',
                    'title' => 'Marks',
                    'url' => $url,
                    'color' => 'marks'
                );
                break;
            case 'events':
                $card_details = array(
                    'icon' => 'event',
                    'title' => 'Events',
                    'url' => base_url('event'),
                    'color' => 'events'
                );
                break;
            case 'reports':
                $card_details = array(
                    'icon' => 'analytics',
                    'title' => 'Reports',
                    'url' => base_url('dashboard/reports'),
                    'color' => 'reports'
                );
                break;
            case 'profile':
                $card_details = array(
                    'icon' => 'person',
                    'title' => 'Profile',
                    'url' => base_url('profile'),
                    'color' => 'profile'
                );
                break;
            case 'settings':
                $card_details = array(
                    'icon' => 'settings',
                    'title' => 'Settings',
                    'url' => base_url('settings'),
                    'color' => 'settings'
                );
                break;
        }
        
        return $card_details;
    }
}