<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Fix Mobilemain Controller and Helper</h1>";

// First, fix the mobile_cards_helper.php file
$helper_file = 'application/helpers/mobile_cards_helper.php';

if (!file_exists($helper_file)) {
    echo "<p>Error: Mobile cards helper file not found</p>";
    exit;
}

// Create a backup of the original helper file
$helper_backup_file = 'application/helpers/mobile_cards_helper_backup_' . date('Y-m-d_H-i-s') . '.php';
if (copy($helper_file, $helper_backup_file)) {
    echo "<p>Created backup of original helper at $helper_backup_file</p>";
} else {
    echo "<p>Failed to create backup of original helper</p>";
    exit;
}

// Read the original helper file
$helper_content = file_get_contents($helper_file);

// Replace all occurrences of ENVIRONMENT with a check
$helper_content = str_replace(
    'if (ENVIRONMENT !== \'production\') {',
    'if (defined(\'ENVIRONMENT\') && ENVIRONMENT !== \'production\') {',
    $helper_content
);

// Write the new content to the helper file
if (file_put_contents($helper_file, $helper_content)) {
    echo "<p>Successfully updated the mobile_cards_helper.php file</p>";
} else {
    echo "<p>Failed to update the mobile_cards_helper.php file</p>";
    exit;
}

// Now, fix the Mobilemain controller
$controller_file = 'application/controllers/Mobilemain.php';

if (!file_exists($controller_file)) {
    echo "<p>Error: Mobilemain controller file not found</p>";
    exit;
}

// Create a backup of the original controller file
$controller_backup_file = 'application/controllers/Mobilemain_backup_' . date('Y-m-d_H-i-s') . '.php';
if (copy($controller_file, $controller_backup_file)) {
    echo "<p>Created backup of original controller at $controller_backup_file</p>";
} else {
    echo "<p>Failed to create backup of original controller</p>";
    exit;
}

// Read the original controller file
$controller_content = file_get_contents($controller_file);

// Check if the file contains the index method
if (strpos($controller_content, 'public function index') === false) {
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
$controller_content = preg_replace($pattern, $replacement, $controller_content);

// Check if the replacement was successful
if ($controller_content === $original_content) {
    echo "<p>Error: Failed to replace the index method</p>";
    exit;
}

// Write the new content to the controller file
if (file_put_contents($controller_file, $controller_content)) {
    echo "<p>Successfully updated the Mobilemain controller</p>";
} else {
    echo "<p>Failed to update the Mobilemain controller</p>";
    exit;
}

echo "<p>Both the Mobilemain controller and mobile_cards_helper have been updated.</p>";
echo "<p>You can now <a href='index.php/mobilemain?force_mobile=1'>test the mobilemain page</a>.</p>";
echo "<p>If you encounter any issues, you can restore the backups by copying:</p>";
echo "<ul>";
echo "<li>$controller_backup_file back to $controller_file</li>";
echo "<li>$helper_backup_file back to $helper_file</li>";
echo "</ul>";

// Create a restore script
$restore_script = <<<EOT
<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Restore Mobilemain Controller and Helper</h1>";

// Restore the original controller
\$controller_file = 'application/controllers/Mobilemain.php';
\$controller_backup_file = '$controller_backup_file';

if (file_exists(\$controller_backup_file)) {
    // Restore from backup
    if (copy(\$controller_backup_file, \$controller_file)) {
        echo "<p>Successfully restored the Mobilemain controller from backup</p>";
    } else {
        echo "<p>Failed to restore the Mobilemain controller from backup</p>";
        exit;
    }
} else {
    echo "<p>Controller backup file not found at \$controller_backup_file</p>";
}

// Restore the original helper
\$helper_file = 'application/helpers/mobile_cards_helper.php';
\$helper_backup_file = '$helper_backup_file';

if (file_exists(\$helper_backup_file)) {
    // Restore from backup
    if (copy(\$helper_backup_file, \$helper_file)) {
        echo "<p>Successfully restored the mobile_cards_helper from backup</p>";
    } else {
        echo "<p>Failed to restore the mobile_cards_helper from backup</p>";
        exit;
    }
} else {
    echo "<p>Helper backup file not found at \$helper_backup_file</p>";
}

echo "<p>The Mobilemain controller and mobile_cards_helper have been restored to their original versions.</p>";
echo "<p>You can now <a href='index.php/mobilemain?force_mobile=1'>test the mobilemain page</a>.</p>";
EOT;

$restore_file = 'restore_mobilemain_and_helper.php';
if (file_put_contents($restore_file, $restore_script)) {
    echo "<p>Created restore script at $restore_file</p>";
} else {
    echo "<p>Failed to create restore script</p>";
}