<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Fix Mobilemain Controller (Direct Version)</h1>";

// Check if the Mobilemain controller exists
$controller_file = 'application/controllers/Mobilemain.php';

if (!file_exists($controller_file)) {
    echo "<p>Error: Mobilemain controller file not found</p>";
    exit;
}

// Create a backup of the original file
$backup_file = 'application/controllers/Mobilemain_backup_' . date('Y-m-d_H-i-s') . '.php';
if (copy($controller_file, $backup_file)) {
    echo "<p>Created backup of original controller at $backup_file</p>";
} else {
    echo "<p>Failed to create backup of original controller</p>";
    exit;
}

// Read the original file
$original_content = file_get_contents($controller_file);

// Check if the file contains the index method
if (strpos($original_content, 'public function index') === false) {
    echo "<p>Error: Could not find the index method in the controller</p>";
    exit;
}

// Replace the index method with our fixed version
$pattern = '/public\s+function\s+index\s*\(\s*\)\s*{.*?}/s';
$replacement = 'public function index()
    {
        // Check if the user is logged in
        if (!is_loggedin()) {
            redirect(base_url(\'authentication\'));
        }

        // Check if the request is coming from a mobile device
        if (!$this->is_mobile_device() && !$this->input->get(\'force_mobile\')) {
            redirect(base_url(\'dashboard\'));
        }

        // Get user role ID - FORCE INTEGER TYPE
        $role_id = (int)$this->session->userdata(\'role_id\');
        
        // Log for debugging
        error_log("Mobile Main - User Role ID: " . $role_id);
        
        // DIRECT DATABASE QUERY - Skip the helper function
        $this->db->select(\'card_item\');
        $this->db->from(\'mobile_cards_config\');
        $this->db->where(\'role_id\', $role_id);
        $this->db->where(\'status\', 1);
        $this->db->order_by(\'card_order\', \'ASC\');
        $query = $this->db->get();
        
        // Log the SQL query
        $sql_query = $this->db->last_query();
        error_log("SQL Query: " . $sql_query);
        
        // Get card items from database
        $card_items = array();
        
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $item) {
                $card_items[] = $item[\'card_item\'];
            }
            error_log("Found " . count($card_items) . " cards in database for role " . $role_id);
        } else {
            // Check if this role has ANY configuration (even disabled cards)
            $this->db->where(\'role_id\', $role_id);
            $check_query = $this->db->get(\'mobile_cards_config\');
            $has_config = ($check_query->num_rows() > 0);
            
            if ($has_config) {
                // This role has configuration but all cards are disabled
                error_log("Role " . $role_id . " has configuration but all cards are disabled");
                $card_items = array(); // Empty array - no cards to show
            } else {
                // No configuration found for this role, use defaults
                error_log("No configuration found for role " . $role_id . ", using defaults");
                
                // Default cards for all roles
                $default_cards = array(\'profile\', \'settings\');
                
                // Role-specific default cards
                if ($role_id == 7) { // Student
                    $student_cards = array(\'attendance\', \'fees\', \'homework\', \'marks\', \'events\');
                    $card_items = array_merge($student_cards, $default_cards);
                } else if ($role_id == 6) { // Parent
                    $parent_cards = array(\'attendance\', \'fees\', \'homework\', \'marks\', \'events\');
                    $card_items = array_merge($parent_cards, $default_cards);
                } else if ($role_id == 3) { // Teacher
                    $teacher_cards = array(\'students\', \'attendance\', \'classes\', \'homework\', \'marks\', \'events\');
                    $card_items = array_merge($teacher_cards, $default_cards);
                } else { // Admin and Superadmin
                    $admin_cards = array(\'students\', \'attendance\', \'fees\', \'classes\', \'homework\', \'marks\', \'events\', \'reports\');
                    $card_items = array_merge($admin_cards, $default_cards);
                }
            }
        }
        
        // Prepare cards data
        $cards = array();
        foreach ($card_items as $card_item) {
            // Skip the \'none\' card item (used as a placeholder)
            if ($card_item !== \'none\') {
                $cards[] = $this->get_card_details($card_item, $role_id);
            }
        }
        
        $data = array(
            \'cards\' => $cards,
            \'role_id\' => $role_id
        );
        
        // Load the mobile main page
        $this->load->view(\'mobilemain\', $data);
    }';

// Replace the index method
$new_content = preg_replace($pattern, $replacement, $original_content);

// Check if the replacement was successful
if ($new_content === $original_content) {
    echo "<p>Error: Failed to replace the index method</p>";
    exit;
}

// Write the new content to the file
if (file_put_contents($controller_file, $new_content)) {
    echo "<p>Successfully updated the Mobilemain controller</p>";
} else {
    echo "<p>Failed to update the Mobilemain controller</p>";
    exit;
}

echo "<p>The Mobilemain controller has been updated with the fixed index method.</p>";
echo "<p>You can now <a href='index.php/mobilemain?force_mobile=1'>test the mobilemain page</a>.</p>";
echo "<p>If you encounter any issues, you can restore the backup by copying $backup_file back to $controller_file.</p>";

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

$restore_file = 'restore_mobilemain_direct.php';
if (file_put_contents($restore_file, $restore_script)) {
    echo "<p>Created restore script at $restore_file</p>";
} else {
    echo "<p>Failed to create restore script</p>";
}