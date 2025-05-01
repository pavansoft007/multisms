<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Bigwala Technologies school management system
 * @version : 1.0
 * @developed by : Bigwala Technologies
 * @support : bigwalatechnologies@bigwallatechnologies.com
 * @author url : https://bigwallatechnologies.com
 * @filename : Mobilemain.php
 * @copyright : Reserved Bigwala Technologiess Team
 */

class Mobilemain extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('mobile_cards');
    }

    public function index()
    {
        // Check if the user is logged in
        if (!is_loggedin()) {
            redirect(base_url('authentication'));
        }

        // Check if the request is coming from a mobile device
        if (!$this->is_mobile_device() && !$this->input->get('force_mobile')) {
            redirect(base_url('dashboard'));
        }

        // Get user role ID - FORCE INTEGER TYPE
        $role_id = (int)$this->session->userdata('role_id');
        
        // Log for debugging
        error_log("Mobile Main - User Role ID: " . $role_id);
        
        // DIRECT DATABASE QUERY - Skip the helper function
        $this->db->select('card_item');
        $this->db->from('mobile_cards_config');
        $this->db->where('role_id', $role_id);
        $this->db->where('status', 1);
        $this->db->order_by('card_order', 'ASC');
        $query = $this->db->get();
        
        // Log the SQL query
        $sql_query = $this->db->last_query();
        error_log("SQL Query: " . $sql_query);
        
        // Get card items from database
        $card_items = array();
        
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $item) {
                $card_items[] = $item['card_item'];
            }
            error_log("Found " . count($card_items) . " cards in database for role " . $role_id);
        } else {
            // Check if this role has ANY configuration (even disabled cards)
            $this->db->where('role_id', $role_id);
            $check_query = $this->db->get('mobile_cards_config');
            $has_config = ($check_query->num_rows() > 0);
            
            if ($has_config) {
                // This role has configuration but all cards are disabled
                error_log("Role " . $role_id . " has configuration but all cards are disabled");
                $card_items = array(); // Empty array - no cards to show
            } else {
                // No configuration found for this role, use defaults
                error_log("No configuration found for role " . $role_id . ", using defaults");
                
                // Default cards for all roles
                $default_cards = array('profile', 'settings');
                
                // Role-specific default cards
                if ($role_id == 7) { // Student
                    $student_cards = array('attendance', 'fees', 'homework', 'marks', 'events');
                    $card_items = array_merge($student_cards, $default_cards);
                } else if ($role_id == 6) { // Parent
                    $parent_cards = array('attendance', 'fees', 'homework', 'marks', 'events');
                    $card_items = array_merge($parent_cards, $default_cards);
                } else if ($role_id == 3) { // Teacher
                    $teacher_cards = array('students', 'attendance', 'classes', 'homework', 'marks', 'events');
                    $card_items = array_merge($teacher_cards, $default_cards);
                } else { // Admin and Superadmin
                    $admin_cards = array('students', 'attendance', 'fees', 'classes', 'homework', 'marks', 'events', 'reports');
                    $card_items = array_merge($admin_cards, $default_cards);
                }
            }
        }
        
        // Prepare cards data
        $cards = array();
        foreach ($card_items as $card_item) {
            // Skip the 'none' card item (used as a placeholder)
            if ($card_item !== 'none') {
                $cards[] = $this->get_card_details($card_item, $role_id);
            }
        }
        
        $data = array(
            'cards' => $cards,
            'role_id' => $role_id
        );
        
        // Load the mobile main page
        $this->load->view('mobilemain', $data);
    }

        // Check if the request is coming from a mobile device
        if (!$this->is_mobile_device() && !$this->input->get('force_mobile')) {
            redirect(base_url('dashboard'));
        }

        // Get user role ID - FORCE INTEGER TYPE
        $role_id = (int)$this->session->userdata('role_id');
        
        // Log for debugging
        error_log("Mobile Main - User Role ID: " . $role_id);
        
        // Add debug output to the page
        echo "<!-- DEBUG: User Role ID: " . $role_id . " -->\n";
        
        // DIRECT DATABASE QUERY - Skip the helper function
        $this->db->select('card_item');
        $this->db->from('mobile_cards_config');
        $this->db->where('role_id', $role_id);
        $this->db->where('status', 1);
        $this->db->order_by('card_order', 'ASC');
        $query = $this->db->get();
        
        // Log the SQL query
        $sql_query = $this->db->last_query();
        error_log("SQL Query: " . $sql_query);
        echo "<!-- DEBUG: SQL Query: " . $sql_query . " -->\n";
        
        // Get card items from database
        $card_items = array();
        $has_config = false;
        
        if ($query->num_rows() > 0) {
            $has_config = true;
            foreach ($query->result_array() as $item) {
                $card_items[] = $item['card_item'];
            }
            error_log("Found " . count($card_items) . " cards in database for role " . $role_id);
            echo "<!-- DEBUG: Found " . count($card_items) . " cards in database for role " . $role_id . " -->\n";
        } else {
            // Check if this role has ANY configuration (even disabled cards)
            $this->db->where('role_id', $role_id);
            $check_query = $this->db->get('mobile_cards_config');
            $has_config = ($check_query->num_rows() > 0);
            
            if ($has_config) {
                // This role has configuration but all cards are disabled
                error_log("Role " . $role_id . " has configuration but all cards are disabled");
                echo "<!-- DEBUG: Role " . $role_id . " has configuration but all cards are disabled -->\n";
                $card_items = array(); // Empty array - no cards to show
            } else {
                // No configuration found for this role, use defaults
                error_log("No configuration found for role " . $role_id . ", using defaults");
                echo "<!-- DEBUG: No configuration found for role " . $role_id . ", using defaults -->\n";
                
                // IMPORTANT: Do not use defaults if we're in production
                // This ensures that only explicitly configured cards are shown
                if (ENVIRONMENT === 'production') {
                    $card_items = array(); // Empty array - no cards to show in production
                } else {
                    // Only use defaults in development for testing
                    $card_items = get_default_cards($role_id);
                }
            }
        }
        
        // Log the retrieved cards
        error_log("Retrieved Cards: " . json_encode($card_items));
        echo "<!-- DEBUG: Retrieved Cards: " . json_encode($card_items) . " -->\n";
        
        // Prepare cards data
        $cards = array();
        foreach ($card_items as $card_item) {
            // Skip the 'none' card item (used as a placeholder)
            if ($card_item !== 'none') {
                $cards[] = get_card_details($card_item, $role_id);
            }
        }
        
        // Log the final cards data
        error_log("Final Cards Data: " . json_encode($cards));
        echo "<!-- DEBUG: Final Cards Data: " . json_encode($cards) . " -->\n";
        
        $data = array(
            'cards' => $cards,
            'role_id' => $role_id
        );
        
        // Load the mobile main page
        $this->load->view('mobilemain', $data);
    }
    
    public function settings()
    {
        // Check if the user is logged in
        if (!is_loggedin()) {
            access_denied();
        }
        
        // Create the mobile_cards_config table if it doesn't exist
        $this->load->dbforge();
        if (!$this->db->table_exists('mobile_cards_config')) {
            $fields = array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'role_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => FALSE
                ),
                'card_item' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => FALSE
                ),
                'card_order' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => FALSE,
                    'default' => 0
                ),
                'status' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'null' => FALSE,
                    'default' => 1
                )
            );
            
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('role_id');
            $this->dbforge->create_table('mobile_cards_config', TRUE);
        }
        
        if ($_POST) {
            // Allow all logged-in users to edit for now
            
            // Save mobile cards configuration
            if ($this->input->post('submit') == 'save') {
                $role_id = $this->input->post('role_id');
                $card_items = $this->input->post('card_items');
                
                if (empty($role_id)) {
                    set_alert('error', 'Please select a user role');
                    redirect(current_url());
                    return;
                }
                
                // Force integer type for role_id
                $role_id = (int)$role_id;
                
                // Log the role ID and card items
                error_log("Saving cards for role ID: " . $role_id);
                error_log("Card items: " . json_encode($card_items));
                
                // Start a transaction
                $this->db->trans_start();
                
                // Delete existing configuration for this role
                $this->db->where('role_id', $role_id);
                $this->db->delete('mobile_cards_config');
                error_log("Deleted existing configuration for role ID: " . $role_id);
                
                // Insert new configuration
                if (!empty($card_items)) {
                    $order = 1;
                    foreach ($card_items as $card_item) {
                        $data = array(
                            'role_id' => $role_id,
                            'card_item' => $card_item,
                            'card_order' => $order,
                            'status' => 1
                        );
                        $result = $this->db->insert('mobile_cards_config', $data);
                        error_log("Inserted card item: " . $card_item . " with order: " . $order . " for role ID: " . $role_id . " - Result: " . ($result ? "Success" : "Failed"));
                        $order++;
                    }
                    
                    // Log the saved items for debugging
                    error_log("Saved " . count($card_items) . " card items for role ID: " . $role_id . ": " . json_encode($card_items));
                } else {
                    error_log("No card items selected for role ID: " . $role_id);
                    
                    // Insert a dummy record to indicate that this role has been configured
                    // This prevents the system from using default cards
                    $data = array(
                        'role_id' => $role_id,
                        'card_item' => 'none',
                        'card_order' => 1,
                        'status' => 0 // Set status to 0 so it won't be displayed
                    );
                    $result = $this->db->insert('mobile_cards_config', $data);
                    error_log("Inserted dummy record for role ID: " . $role_id . " - Result: " . ($result ? "Success" : "Failed"));
                }
                
                // Force database to commit changes
                $this->db->trans_complete();
                
                // Clear cache
                $this->load->driver('cache', array('adapter' => 'file'));
                $cache_key = 'mobile_cards_' . $role_id;
                $result = $this->cache->delete($cache_key);
                error_log("Cache cleared for role ID: " . $role_id . " - Result: " . ($result ? "Success" : "Failed"));
                
                set_alert('success', translate('the_configuration_has_been_updated'));
                redirect(current_url());
            }
        }
        
        // Get all roles
        $this->db->select('*');
        $this->data['roles'] = $this->db->get('roles')->result_array();
        
        // Get all available card items
        $this->data['all_cards'] = array(
            'students' => 'Students',
            'attendance' => 'Attendance',
            'fees' => 'Fees',
            'classes' => 'Classes',
            'homework' => 'Homework',
            'marks' => 'Marks',
            'events' => 'Events',
            'reports' => 'Reports',
            'profile' => 'Profile',
            'settings' => 'Settings'
        );
        
        $this->data['title'] = 'Mobile Cards Settings';
        $this->data['sub_page'] = 'settings/mobile_cards_settings';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }
    
    public function get_card_items()
    {
        if ($_POST) {
            $role_id = (int)$this->input->post('role_id');
            
            // Log for debugging
            error_log("Getting card items for role ID: " . $role_id);
            
            // Get all available card items
            $all_card_items = array(
                'students' => translate('students'),
                'attendance' => translate('attendance'),
                'fees' => translate('fees'),
                'classes' => translate('classes'),
                'homework' => translate('homework'),
                'marks' => translate('marks'),
                'events' => translate('events'),
                'reports' => translate('reports'),
                'profile' => translate('profile'),
                'settings' => translate('settings')
            );
            
            // Clear cache for testing
            $this->load->driver('cache', array('adapter' => 'file'));
            $cache_key = 'mobile_cards_' . $role_id;
            $this->cache->delete($cache_key);
            
            // Get selected card items for this role directly from database
            $this->db->select('card_item');
            $this->db->from('mobile_cards_config');
            $this->db->where('role_id', $role_id);
            $this->db->where('status', 1);
            $this->db->order_by('card_order', 'ASC');
            $query = $this->db->get();
            
            error_log("SQL Query: " . $this->db->last_query());
            
            $selected_items = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $item) {
                    $selected_items[] = $item['card_item'];
                }
                error_log("Found " . count($selected_items) . " cards in database for role " . $role_id . ": " . json_encode($selected_items));
            } else {
                // Check if this role has ANY configuration (even disabled cards)
                $this->db->where('role_id', $role_id);
                $check_query = $this->db->get('mobile_cards_config');
                $has_config = ($check_query->num_rows() > 0);
                
                if ($has_config) {
                    // This role has configuration but all cards are disabled
                    error_log("Role " . $role_id . " has configuration but all cards are disabled");
                    $selected_items = array(); // Empty array - no cards to show
                } else {
                    // If no items found in database, use empty array
                    $selected_items = array();
                    error_log("No cards found in database for role " . $role_id . ", using empty array");
                }
            }
            
            $data = array(
                'all_items' => $all_card_items,
                'selected_items' => $selected_items
            );
            
            error_log("Returning card items: " . json_encode($data));
            echo json_encode($data);
        }
    }
    
    // Function to detect if the request is coming from a mobile device
    private function is_mobile_device() {
        // First check the cookie set by JavaScript
        if ($this->input->cookie('is_mobile') === 'true') {
            return true;
        }
        
        // Then check user agent
        $user_agent = $this->input->server('HTTP_USER_AGENT');
        $mobile_agents = array(
            'Android', 'webOS', 'iPhone', 'iPad', 'iPod', 'BlackBerry', 'Windows Phone',
            'Mobile', 'Opera Mini', 'Opera Mobi', 'IEMobile', 'Silk', 'Kindle'
        );
        
        foreach ($mobile_agents as $agent) {
            if (stripos($user_agent, $agent) !== false) {
                return true;
            }
        }
        
        // More comprehensive check for mobile devices
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $user_agent)) {
            return true;
        }
        
        // Check for certain mobile device prefixes
        if (preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($user_agent, 0, 4))) {
            return true;
        }
        
        // Check screen width (this is a fallback, as the JavaScript detection is more reliable)
        if (isset($_COOKIE['screen_width']) && $_COOKIE['screen_width'] <= 767) {
            return true;
        }
        
        return false;
    }
    
    // Debug function to check the database
    public function debug() {
        // Only allow in development environment
        if (ENVIRONMENT !== 'development' && ENVIRONMENT !== 'testing') {
            show_404();
        }
        
        echo "<h1>Mobile Cards Debug</h1>";
        
        // Check if table exists
        if (!$this->db->table_exists('mobile_cards_config')) {
            echo "<p>Table mobile_cards_config does not exist!</p>";
            return;
        }
        
        echo "<h2>Database Table Structure</h2>";
        $fields = $this->db->field_data('mobile_cards_config');
        echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Max Length</th><th>Primary Key</th></tr>";
        foreach ($fields as $field) {
            echo "<tr><td>{$field->name}</td><td>{$field->type}</td><td>{$field->max_length}</td><td>" . ($field->primary_key ? 'Yes' : 'No') . "</td></tr>";
        }
        echo "</table>";
        
        echo "<h2>All Records</h2>";
        $query = $this->db->get('mobile_cards_config');
        if ($query->num_rows() > 0) {
            echo "<table border='1'><tr><th>ID</th><th>Role ID</th><th>Card Item</th><th>Card Order</th><th>Status</th></tr>";
            foreach ($query->result() as $row) {
                echo "<tr><td>{$row->id}</td><td>{$row->role_id}</td><td>{$row->card_item}</td><td>{$row->card_order}</td><td>{$row->status}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No records found in the table.</p>";
        }
        
        echo "<h2>Role-specific Cards</h2>";
        $roles = $this->db->get('roles')->result();
        foreach ($roles as $role) {
            echo "<h3>Role: {$role->name} (ID: {$role->id})</h3>";
            
            $this->db->where('role_id', $role->id);
            $this->db->where('status', 1);
            $this->db->order_by('card_order', 'ASC');
            $cards_query = $this->db->get('mobile_cards_config');
            
            if ($cards_query->num_rows() > 0) {
                echo "<ul>";
                foreach ($cards_query->result() as $card) {
                    echo "<li>{$card->card_item} (Order: {$card->card_order})</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No cards configured for this role. Using defaults:</p>";
                $defaults = get_default_cards($role->id);
                echo "<ul>";
                foreach ($defaults as $card) {
                    echo "<li>{$card}</li>";
                }
                echo "</ul>";
            }
        }
        
        // Current user info
        echo "<h2>Current User Info</h2>";
        $current_role_id = $this->session->userdata('role_id');
        echo "<p>Current user role ID: {$current_role_id}</p>";
        
        // Test get_mobile_cards function
        echo "<h2>Test get_mobile_cards Function</h2>";
        $cards = get_mobile_cards($current_role_id);
        echo "<p>Cards returned by get_mobile_cards for role {$current_role_id}:</p>";
        echo "<ul>";
        foreach ($cards as $card) {
            echo "<li>{$card}</li>";
        }
        echo "</ul>";
    }
}