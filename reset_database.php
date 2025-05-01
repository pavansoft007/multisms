Fatal error: Uncaught Error: Call to undefined method Payment::getRiderPaymentById() in C:\xampp\htdocs\manpower\rider\payment_details.php:39 Stack trace: #0 {main} thrown in C:\xampp\htdocs\manpower\rider\payment_details.php on line 39<?php
// Reset Database Configuration
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Reset Database Configuration</h1>";

// Function to reset database.php
function reset_database_php() {
    echo "<h3>Resetting database.php</h3>";
    
    $database_file = 'application/config/database.php';
    
    if (!file_exists($database_file)) {
        echo "<p style='color:red'>Database configuration file not found: $database_file</p>";
        return false;
    }
    
    // Create backup if it doesn't exist
    $backup_file = $database_file . '.bak';
    if (!file_exists($backup_file)) {
        if (copy($database_file, $backup_file)) {
            echo "<p style='color:green'>Created backup of database configuration file: $backup_file</p>";
        } else {
            echo "<p style='color:red'>Failed to create backup of database configuration file!</p>";
            return false;
        }
    } else {
        echo "<p style='color:orange'>Backup file already exists: $backup_file</p>";
    }
    
    // Create a new database.php file with safe settings
    $new_config = '<?php
defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the \'Database Connection\'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	[\'dsn\']      The full DSN string describe a connection to the database.
|	[\'hostname\'] The hostname of your database server.
|	[\'username\'] The username used to connect to the database
|	[\'password\'] The password used to connect to the database
|	[\'database\'] The name of the database you want to connect to
|	[\'dbdriver\'] The database driver. e.g.: mysqli.
|			Currently supported:
|				 cubrid, ibase, mssql, mysql, mysqli, oci8,
|				 odbc, pdo, postgre, sqlite, sqlite3, sqlsrv
|	[\'dbprefix\'] You can add an optional prefix, which will be added
|				 to the table name when using the  Query Builder class
|	[\'pconnect\'] TRUE/FALSE - Whether to use a persistent connection
|	[\'db_debug\'] TRUE/FALSE - Whether database errors should be displayed.
|	[\'cache_on\'] TRUE/FALSE - Enables/disables query caching
|	[\'cachedir\'] The path to the folder where cache files should be stored
|	[\'char_set\'] The character set used in communicating with the database
|	[\'dbcollat\'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	[\'swap_pre\'] A default table prefix that should be swapped with the dbprefix
|	[\'encrypt\']  Whether or not to use an encrypted connection.
|
|			\'mysql\' (deprecated), \'sqlsrv\' and \'pdo/sqlsrv\' drivers accept TRUE/FALSE
|			\'mysqli\' and \'pdo/mysql\' drivers accept an array with the following options:
|
|				\'ssl_key\'    - Path to the private key file
|				\'ssl_cert\'   - Path to the public key certificate file
|				\'ssl_ca\'     - Path to the certificate authority file
|				\'ssl_capath\' - Path to a directory containing trusted CA certificates in PEM format
|				\'ssl_cipher\' - List of *allowed* ciphers to be used for the encryption, separated by colons (\':\')
|				\'ssl_verify\' - TRUE/FALSE; Whether verify the server certificate or not
|
|	[\'compress\'] Whether or not to use client compression (MySQL only)
|	[\'stricton\'] TRUE/FALSE - forces \'Strict Mode\' connections
|							- good for ensuring strict SQL while developing
|	[\'ssl_options\']	Used to set various SSL options that can be used when making SSL connections.
|	[\'failover\'] array - A array with 0 or more data for connections if the main should fail.
|	[\'save_queries\'] TRUE/FALSE - Whether to "save" all executed queries.
| 				NOTE: Disabling this will also effectively disable both
| 				$this->db->last_query() and profiling of DB queries.
| 				When you run a query, with this setting set to TRUE (default),
| 				CodeIgniter will store the SQL statement for debugging purposes.
| 				However, this may cause high memory usage, especially if you run
| 				a lot of SQL queries ... disable this to avoid that problem.
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the \'default\' group).
|
| The $query_builder variables lets you determine whether or not to load
| the query builder class.
*/
$active_group = \'default\';
$query_builder = TRUE;

$db[\'default\'] = array(
	\'dsn\'	=> \'\',
	\'hostname\' => \'localhost\',
	\'username\' => \'root\',
	\'password\' => \'\',
	\'database\' => \'multisms\',
	\'dbdriver\' => \'mysqli\',
	\'dbprefix\' => \'\',
	\'pconnect\' => FALSE,
	\'db_debug\' => FALSE,
	\'cache_on\' => FALSE,
	\'cachedir\' => \'\',
	\'char_set\' => \'utf8\',
	\'dbcollat\' => \'utf8_general_ci\',
	\'swap_pre\' => \'\',
	\'encrypt\' => FALSE,
	\'compress\' => FALSE,
	\'stricton\' => FALSE,
	\'failover\' => array(),
	\'save_queries\' => FALSE
);';
    
    if (file_put_contents($database_file, $new_config)) {
        echo "<p style='color:green'>Reset database configuration with safe settings.</p>";
    } else {
        echo "<p style='color:red'>Failed to reset database configuration!</p>";
        return false;
    }
    
    return true;
}

// Function to create a minimal autoload.php
function create_minimal_autoload() {
    echo "<h3>Creating minimal autoload.php</h3>";
    
    $autoload_file = 'application/config/autoload.php';
    
    if (!file_exists($autoload_file)) {
        echo "<p style='color:red'>Autoload configuration file not found: $autoload_file</p>";
        return false;
    }
    
    // Create backup if it doesn't exist
    $backup_file = $autoload_file . '.bak';
    if (!file_exists($backup_file)) {
        if (copy($autoload_file, $backup_file)) {
            echo "<p style='color:green'>Created backup of autoload configuration file: $backup_file</p>";
        } else {
            echo "<p style='color:red'>Failed to create backup of autoload configuration file!</p>";
            return false;
        }
    } else {
        echo "<p style='color:orange'>Backup file already exists: $backup_file</p>";
    }
    
    // Read the autoload configuration file
    $autoload = file_get_contents($autoload_file);
    
    // Modify the libraries autoload to include our database helper
    $modified_autoload = preg_replace(
        '/\$autoload\[\'helper\'\]\s*=\s*array\((.*?)\);/s',
        '$autoload[\'helper\'] = array($1, \'db\');',
        $autoload
    );
    
    if ($modified_autoload !== $autoload) {
        if (file_put_contents($autoload_file, $modified_autoload)) {
            echo "<p style='color:green'>Modified autoload configuration to include database helper.</p>";
        } else {
            echo "<p style='color:red'>Failed to modify autoload configuration file!</p>";
            return false;
        }
    } else {
        echo "<p style='color:orange'>No changes needed in autoload configuration.</p>";
    }
    
    return true;
}

// Function to create a minimal routes.php
function create_minimal_routes() {
    echo "<h3>Creating minimal routes.php</h3>";
    
    $routes_file = 'application/config/routes.php';
    
    if (!file_exists($routes_file)) {
        echo "<p style='color:red'>Routes configuration file not found: $routes_file</p>";
        return false;
    }
    
    // Create backup if it doesn't exist
    $backup_file = $routes_file . '.bak';
    if (!file_exists($backup_file)) {
        if (copy($routes_file, $backup_file)) {
            echo "<p style='color:green'>Created backup of routes configuration file: $backup_file</p>";
        } else {
            echo "<p style='color:red'>Failed to create backup of routes configuration file!</p>";
            return false;
        }
    } else {
        echo "<p style='color:orange'>Backup file already exists: $backup_file</p>";
    }
    
    // Read the routes configuration file
    $routes = file_get_contents($routes_file);
    
    // Add a route for the minimal controller
    $modified_routes = preg_replace(
        '/\$route\[\'default_controller\'\]\s*=\s*\'(.*?)\';/',
        '$route[\'default_controller\'] = \'minimal\';',
        $routes
    );
    
    if ($modified_routes !== $routes) {
        if (file_put_contents($routes_file, $modified_routes)) {
            echo "<p style='color:green'>Modified routes configuration to use minimal controller.</p>";
        } else {
            echo "<p style='color:red'>Failed to modify routes configuration file!</p>";
            return false;
        }
    } else {
        echo "<p style='color:orange'>No changes needed in routes configuration.</p>";
    }
    
    return true;
}

// Function to create a minimal controller
function create_minimal_controller() {
    echo "<h3>Creating minimal controller</h3>";
    
    $controller_file = 'application/controllers/Minimal.php';
    
    // Create the directory if it doesn't exist
    $dir = dirname($controller_file);
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777, true)) {
            echo "<p style='color:red'>Failed to create directory: $dir</p>";
            return false;
        }
    }
    
    // Create the controller file
    $controller_content = '<?php
defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');

class Minimal extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Set error handling
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            log_message(\'error\', "Error $errno: $errstr in $errfile on line $errline");
            return true;
        });
    }
    
    public function index() {
        $data = array();
        $data[\'title\'] = \'Minimal Controller\';
        $data[\'message\'] = \'This is a minimal controller that doesn\\\'t use the database.\';
        
        $this->load->view(\'minimal/header\', $data);
        $this->load->view(\'minimal/index\', $data);
        $this->load->view(\'minimal/footer\', $data);
    }
    
    public function test_db() {
        $data = array();
        $data[\'title\'] = \'Database Test\';
        $data[\'message\'] = \'Testing database connection...\';
        
        try {
            // Test database connection
            $this->load->database();
            
            if ($this->db->conn_id) {
                $data[\'db_status\'] = \'Connected to database successfully.\';
                
                // Test a simple query
                $query = $this->db->query(\'SELECT 1 as test\');
                
                if ($query) {
                    $data[\'query_status\'] = \'Simple query executed successfully.\';
                    $data[\'query_result\'] = $query->row();
                } else {
                    $data[\'query_status\'] = \'Failed to execute simple query: \' . $this->db->error()[\'message\'];
                }
            } else {
                $data[\'db_status\'] = \'Failed to connect to database.\';
            }
        } catch (Exception $e) {
            $data[\'db_status\'] = \'Exception: \' . $e->getMessage();
        }
        
        $this->load->view(\'minimal/header\', $data);
        $this->load->view(\'minimal/db_test\', $data);
        $this->load->view(\'minimal/footer\', $data);
    }
}';
    
    if (file_put_contents($controller_file, $controller_content)) {
        echo "<p style='color:green'>Created minimal controller: $controller_file</p>";
    } else {
        echo "<p style='color:red'>Failed to create minimal controller!</p>";
        return false;
    }
    
    return true;
}

// Function to create minimal views
function create_minimal_views() {
    echo "<h3>Creating minimal views</h3>";
    
    $views_dir = 'application/views/minimal';
    
    // Create the directory if it doesn't exist
    if (!is_dir($views_dir)) {
        if (!mkdir($views_dir, 0777, true)) {
            echo "<p style='color:red'>Failed to create directory: $views_dir</p>";
            return false;
        }
    }
    
    // Create the header view
    $header_file = $views_dir . '/header.php';
    $header_content = '<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3498db;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
        }
        .nav {
            background-color: #2980b9;
            padding: 10px;
        }
        .nav a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
        }
        .content {
            background-color: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-danger {
            background-color: #e74c3c;
        }
        .success {
            color: #2ecc71;
        }
        .error {
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Minimal Application</h1>
        <a href="<?php echo base_url(); ?>emergency_dashboard.php" class="btn">Emergency Dashboard</a>
    </div>
    <div class="nav">
        <a href="<?php echo base_url(); ?>index.php/minimal">Home</a>
        <a href="<?php echo base_url(); ?>index.php/minimal/test_db">Test Database</a>
        <a href="<?php echo base_url(); ?>minimal_app.php">Minimal App</a>
        <a href="<?php echo base_url(); ?>emergency_dashboard.php">Emergency Dashboard</a>
    </div>
    <div class="container">';
    
    if (file_put_contents($header_file, $header_content)) {
        echo "<p style='color:green'>Created header view: $header_file</p>";
    } else {
        echo "<p style='color:red'>Failed to create header view!</p>";
        return false;
    }
    
    // Create the footer view
    $footer_file = $views_dir . '/footer.php';
    $footer_content = '    </div>
    <div class="container">
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #777;">
            <p>Minimal Application &copy; <?php echo date(\'Y\'); ?></p>
        </div>
    </div>
</body>
</html>';
    
    if (file_put_contents($footer_file, $footer_content)) {
        echo "<p style='color:green'>Created footer view: $footer_file</p>";
    } else {
        echo "<p style='color:red'>Failed to create footer view!</p>";
        return false;
    }
    
    // Create the index view
    $index_file = $views_dir . '/index.php';
    $index_content = '<div class="content">
    <h2><?php echo $title; ?></h2>
    <p><?php echo $message; ?></p>
    
    <h3>Available Tools</h3>
    <ul>
        <li><a href="<?php echo base_url(); ?>index.php/minimal/test_db">Test Database Connection</a></li>
        <li><a href="<?php echo base_url(); ?>emergency_dashboard.php">Emergency Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>minimal_app.php">Minimal Application</a></li>
        <li><a href="<?php echo base_url(); ?>simple_mysql_check.php">MySQL Check</a></li>
        <li><a href="<?php echo base_url(); ?>simple_error_check.php">Error Check</a></li>
        <li><a href="<?php echo base_url(); ?>fix_common_issues.php">Fix Common Issues</a></li>
    </ul>
</div>';
    
    if (file_put_contents($index_file, $index_content)) {
        echo "<p style='color:green'>Created index view: $index_file</p>";
    } else {
        echo "<p style='color:red'>Failed to create index view!</p>";
        return false;
    }
    
    // Create the db_test view
    $db_test_file = $views_dir . '/db_test.php';
    $db_test_content = '<div class="content">
    <h2><?php echo $title; ?></h2>
    <p><?php echo $message; ?></p>
    
    <h3>Database Status</h3>
    <p class="<?php echo isset($db_status) && strpos($db_status, \'Connected\') !== false ? \'success\' : \'error\'; ?>">
        <?php echo isset($db_status) ? $db_status : \'Unknown\'; ?>
    </p>
    
    <?php if (isset($query_status)): ?>
        <h3>Query Status</h3>
        <p class="<?php echo strpos($query_status, \'successfully\') !== false ? \'success\' : \'error\'; ?>">
            <?php echo $query_status; ?>
        </p>
        
        <?php if (isset($query_result)): ?>
            <h3>Query Result</h3>
            <pre><?php print_r($query_result); ?></pre>
        <?php endif; ?>
    <?php endif; ?>
    
    <p><a href="<?php echo base_url(); ?>index.php/minimal" class="btn">Back to Home</a></p>
</div>';
    
    if (file_put_contents($db_test_file, $db_test_content)) {
        echo "<p style='color:green'>Created db_test view: $db_test_file</p>";
    } else {
        echo "<p style='color:red'>Failed to create db_test view!</p>";
        return false;
    }
    
    return true;
}

// Main script
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'reset') {
        // Reset database.php
        reset_database_php();
        
        // Create minimal autoload.php
        create_minimal_autoload();
        
        // Create minimal routes.php
        create_minimal_routes();
        
        // Create minimal controller
        create_minimal_controller();
        
        // Create minimal views
        create_minimal_views();
        
        echo "<h2>Database Configuration Reset</h2>";
        echo "<p>The database configuration has been reset with safe settings.</p>";
        echo "<p>A minimal CodeIgniter application has been created that doesn't rely on the database.</p>";
        echo "<p>To test if these changes have fixed the issue, try the following:</p>";
        echo "<ol>";
        echo "<li>Restart MySQL and Apache using the XAMPP Control Panel</li>";
        echo "<li>Access the minimal CodeIgniter application: <a href='index.php/minimal'>index.php/minimal</a></li>";
        echo "<li>If that works, try the database test: <a href='index.php/minimal/test_db'>index.php/minimal/test_db</a></li>";
        echo "</ol>";
    } elseif ($_GET['action'] === 'restore') {
        // Restore database.php from backup
        $database_file = 'application/config/database.php';
        $backup_file = $database_file . '.bak';
        
        if (file_exists($backup_file)) {
            if (copy($backup_file, $database_file)) {
                echo "<p style='color:green'>Restored database configuration from backup.</p>";
            } else {
                echo "<p style='color:red'>Failed to restore database configuration from backup!</p>";
            }
        } else {
            echo "<p style='color:red'>Backup file not found: $backup_file</p>";
        }
        
        // Restore autoload.php from backup
        $autoload_file = 'application/config/autoload.php';
        $backup_file = $autoload_file . '.bak';
        
        if (file_exists($backup_file)) {
            if (copy($backup_file, $autoload_file)) {
                echo "<p style='color:green'>Restored autoload configuration from backup.</p>";
            } else {
                echo "<p style='color:red'>Failed to restore autoload configuration from backup!</p>";
            }
        } else {
            echo "<p style='color:red'>Backup file not found: $backup_file</p>";
        }
        
        // Restore routes.php from backup
        $routes_file = 'application/config/routes.php';
        $backup_file = $routes_file . '.bak';
        
        if (file_exists($backup_file)) {
            if (copy($backup_file, $routes_file)) {
                echo "<p style='color:green'>Restored routes configuration from backup.</p>";
            } else {
                echo "<p style='color:red'>Failed to restore routes configuration from backup!</p>";
            }
        } else {
            echo "<p style='color:red'>Backup file not found: $backup_file</p>";
        }
        
        echo "<h2>Database Configuration Restored</h2>";
        echo "<p>The database configuration has been restored from backup.</p>";
    }
} else {
    echo "<p>Choose an action:</p>";
    echo "<ul>";
    echo "<li><a href='?action=reset' style='color: red; font-weight: bold;'>Reset Database Configuration</a> - This will reset the database configuration with safe settings and create a minimal CodeIgniter application.</li>";
    echo "<li><a href='?action=restore' style='color: green; font-weight: bold;'>Restore Database Configuration</a> - This will restore the original database configuration from backup.</li>";
    echo "</ul>";
    
    echo "<p><strong>Warning:</strong> Resetting the database configuration will modify your CodeIgniter application. Make sure you have backups before proceeding.</p>";
}

// Back to dashboard link
echo "<p><a href='emergency_dashboard.php' style='display: inline-block; padding: 10px 15px; background-color: #2ecc71; color: white; text-decoration: none; border-radius: 4px;'>Back to Dashboard</a></p>";
?>