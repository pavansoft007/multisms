<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Replace Mobilemain Controller</h1>";

// Define the source and destination files
$source_file = 'application/controllers/Mobilemain_new.php';
$dest_file = 'application/controllers/Mobilemain.php';

// Check if the source file exists
if (!file_exists($source_file)) {
    // Create the source file
    $source_content = <<<'EOT'
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mobilemain Controller
 * 
 * This controller handles the mobile main page
 */
class Mobilemain extends CI_Controller {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
    }
    
    /**
     * Index method - displays the mobile main page
     */
    public function index() {
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
    
    /**
     * Get card details (icon, title, URL, color)
     * 
     * @param string $card_item The card item identifier
     * @param int $role_id The role ID (optional)
     * @return array Card details
     */
    private function get_card_details($card_item, $role_id = 0) {
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
    
    /**
     * Check if the request is coming from a mobile device
     * 
     * @return bool True if mobile device
     */
    private function is_mobile_device() {
        $user_agent = $this->input->user_agent();
        
        // Check for mobile user agent
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $user_agent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($user_agent, 0, 4))) {
            return true;
        }
        
        // Check for mobile viewport
        if ($this->input->get('force_mobile')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Get card items for a specific role
     * 
     * @param int $role_id The role ID
     * @return array Array of card items
     */
    public function get_card_items() {
        // Check if the request is AJAX
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        // Get role ID from POST
        $role_id = $this->input->post('role_id');
        if (!$role_id) {
            $role_id = $this->session->userdata('role_id');
        }
        
        // Force integer type
        $role_id = (int)$role_id;
        
        // Get card items from database
        $this->db->select('card_item');
        $this->db->from('mobile_cards_config');
        $this->db->where('role_id', $role_id);
        $this->db->where('status', 1);
        $this->db->order_by('card_order', 'ASC');
        $query = $this->db->get();
        
        $selected_items = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $item) {
                $selected_items[] = $item['card_item'];
            }
        }
        
        // Return JSON response
        $response = array(
            'success' => true,
            'selected_items' => $selected_items
        );
        
        echo json_encode($response);
    }
    
    /**
     * Settings page
     */
    public function settings() {
        // Check if the user is logged in
        if (!is_loggedin()) {
            redirect(base_url('authentication'));
        }
        
        // Check if the user is admin
        if ($this->session->userdata('role_id') != 1) {
            show_404();
        }
        
        // Get all roles
        $roles = array(
            1 => 'Admin',
            2 => 'Teacher',
            3 => 'Student',
            4 => 'Parent',
            5 => 'Accountant',
            6 => 'Librarian',
            7 => 'Staff'
        );
        
        // Get all available card items
        $all_cards = array(
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
        
        // Get selected role ID from query string or form
        $selected_role_id = $this->input->get('role_id');
        if (!$selected_role_id) {
            $selected_role_id = $this->input->post('role_id');
        }
        if (!$selected_role_id) {
            $selected_role_id = 1;
        }
        
        // Force integer type
        $selected_role_id = (int)$selected_role_id;
        
        // Process form submission
        if ($this->input->post('save')) {
            $role_id = (int)$this->input->post('role_id');
            $card_items = $this->input->post('card_items');
            
            // Start a transaction
            $this->db->trans_start();
            
            // Delete existing configuration for this role
            $this->db->where('role_id', $role_id);
            $this->db->delete('mobile_cards_config');
            
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
                    $this->db->insert('mobile_cards_config', $data);
                    $order++;
                }
            } else {
                // Insert a dummy record to indicate that this role has been configured
                // This prevents the system from using default cards
                $data = array(
                    'role_id' => $role_id,
                    'card_item' => 'none',
                    'card_order' => 1,
                    'status' => 0
                );
                $this->db->insert('mobile_cards_config', $data);
            }
            
            // Complete the transaction
            $this->db->trans_complete();
            
            // Set flash message
            $this->session->set_flashdata('success', 'Settings saved successfully!');
            
            // Redirect to prevent form resubmission
            redirect(base_url('mobilemain/settings?role_id=' . $role_id));
        }
        
        // Get selected card items for this role
        $this->db->select('card_item');
        $this->db->from('mobile_cards_config');
        $this->db->where('role_id', $selected_role_id);
        $this->db->where('status', 1);
        $this->db->order_by('card_order', 'ASC');
        $query = $this->db->get();
        
        $selected_items = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $item) {
                $selected_items[] = $item['card_item'];
            }
        }
        
        // Prepare data for the view
        $data = array(
            'roles' => $roles,
            'all_cards' => $all_cards,
            'selected_role_id' => $selected_role_id,
            'selected_items' => $selected_items
        );
        
        // Load the settings view
        $this->load->view('mobilemain_settings', $data);
    }
}
EOT;
    
    if (file_put_contents($source_file, $source_content)) {
        echo "<p>Created source file at $source_file</p>";
    } else {
        echo "<p>Failed to create source file</p>";
        exit;
    }
}

// Create a backup of the destination file
$backup_file = 'application/controllers/Mobilemain_backup_' . date('Y-m-d_H-i-s') . '.php';
if (file_exists($dest_file) && copy($dest_file, $backup_file)) {
    echo "<p>Created backup of original controller at $backup_file</p>";
} else {
    echo "<p>Failed to create backup of original controller</p>";
    exit;
}

// Copy the source file to the destination
if (copy($source_file, $dest_file)) {
    echo "<p>Successfully replaced the Mobilemain controller</p>";
} else {
    echo "<p>Failed to replace the Mobilemain controller</p>";
    exit;
}

echo "<p>The Mobilemain controller has been replaced with a new version that doesn't rely on the helper function.</p>";
echo "<p>You can now <a href='index.php/mobilemain?force_mobile=1'>test the mobilemain page</a>.</p>";
echo "<p>If you encounter any issues, you can restore the backup by copying $backup_file back to $dest_file.</p>";

// Create a restore script
$restore_script = <<<EOT
<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Restore Mobilemain Controller</h1>";

// Restore the original controller
\$controller_file = 'application/controllers/Mobilemain.php';
\$backup_file = '$backup_file';

if (file_exists(\$backup_file)) {
    // Restore from backup
    if (copy(\$backup_file, \$controller_file)) {
        echo "<p>Successfully restored the Mobilemain controller from backup</p>";
    } else {
        echo "<p>Failed to restore the Mobilemain controller from backup</p>";
        exit;
    }
    
    echo "<p>The Mobilemain controller has been restored to its original version.</p>";
    echo "<p>You can now <a href='index.php/mobilemain?force_mobile=1'>test the mobilemain page</a>.</p>";
} else {
    echo "<p>Backup file not found at \$backup_file</p>";
}
EOT;

$restore_file = 'restore_mobilemain_replace.php';
if (file_put_contents($restore_file, $restore_script)) {
    echo "<p>Created restore script at $restore_file</p>";
} else {
    echo "<p>Failed to create restore script</p>";
}