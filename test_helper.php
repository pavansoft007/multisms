<?php
// Initialize CodeIgniter
define('BASEPATH', true);
include_once 'application/config/constants.php';

// Include the helper file
include_once 'application/helpers/mobile_cards_helper.php';

// Test the helper functions
echo "<h1>Mobile Cards Helper Test</h1>";

// Test get_default_cards function
echo "<h2>Default Cards</h2>";
$role_ids = array(1, 3, 6, 7);
foreach ($role_ids as $role_id) {
    echo "<h3>Role ID: $role_id</h3>";
    echo "<ul>";
    
    // We need to define the base_url function since it's used in the helper
    if (!function_exists('base_url')) {
        function base_url($uri = '') {
            return 'http://localhost/multisms/' . $uri;
        }
    }
    
    // We need to define the get_instance function since it's used in the helper
    if (!function_exists('get_instance')) {
        function &get_instance() {
            static $instance;
            if (!$instance) {
                $instance = new stdClass();
                $instance->db = new stdClass();
                $instance->db->table_exists = function($table) {
                    return true;
                };
                $instance->db->select = function($field) {
                    return $instance->db;
                };
                $instance->db->from = function($table) {
                    return $instance->db;
                };
                $instance->db->where = function($field, $value) {
                    return $instance->db;
                };
                $instance->db->order_by = function($field, $order) {
                    return $instance->db;
                };
                $instance->db->get = function() {
                    $result = new stdClass();
                    $result->num_rows = function() {
                        return 0;
                    };
                    $result->result_array = function() {
                        return array();
                    };
                    return $result;
                };
                $instance->db->last_query = function() {
                    return "SELECT * FROM mobile_cards_config WHERE role_id = 1 AND status = 1 ORDER BY card_order ASC";
                };
            }
            return $instance;
        }
    }
    
    try {
        $default_cards = get_default_cards($role_id);
        foreach ($default_cards as $card) {
            echo "<li>$card</li>";
        }
    } catch (Exception $e) {
        echo "<li>Error: " . $e->getMessage() . "</li>";
    }
    
    echo "</ul>";
}

// Test get_card_details function
echo "<h2>Card Details</h2>";
$card_items = array('students', 'attendance', 'fees', 'classes', 'homework', 'marks', 'events', 'reports', 'profile', 'settings');
foreach ($card_items as $card_item) {
    echo "<h3>Card Item: $card_item</h3>";
    echo "<ul>";
    
    try {
        $card_details = get_card_details($card_item, 1);
        echo "<li>Icon: " . $card_details['icon'] . "</li>";
        echo "<li>Title: " . $card_details['title'] . "</li>";
        echo "<li>URL: " . $card_details['url'] . "</li>";
        echo "<li>Color: " . $card_details['color'] . "</li>";
    } catch (Exception $e) {
        echo "<li>Error: " . $e->getMessage() . "</li>";
    }
    
    echo "</ul>";
}