<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#6750A4">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/material-design-3.css'); ?>">
    <style>
        /* Mobile-specific overrides */
        @media (max-width: 767px) {
            body {
                -webkit-tap-highlight-color: transparent;
                overscroll-behavior: none;
            }
            
            .md3-app-bar {
                padding: 12px 16px;
            }
            
            .md3-container {
                padding: 12px;
            }
            
            .md3-module-card {
                border-radius: 16px;
            }
            
            .md3-bottom-nav {
                padding: 6px 0;
            }
        }
    </style>
</head>
<body>
    <div class="md3-app-bar">
        <div>
            <h1 class="md3-app-bar-title"><?php echo translate('main_menu'); ?></h1>
            <p class="md3-app-bar-subtitle"><?php echo translate('welcome') . ', ' . $user_name; ?> (<?php echo $user_role; ?>)</p>
        </div>
    </div>
    
    <div class="md3-container">
        <?php
        // Display all menu items in a single grid
        echo '<div class="md3-module-grid">';
        
        // Loop through all menu items
        foreach ($menu_items as $item) {
            $name = $item['name'];
            $url = $item['url'];
            $icon = $item['icon'];
            $desc = $item['desc'];
            
            // Convert icon class if needed (for compatibility with both icon sets)
            if (strpos($icon, 'icons icon-') !== false) {
                $icon_class = str_replace('icons icon-', 'fas fa-', $icon);
                // Map some specific icons
                $icon_map = [
                    'fas fa-grid' => 'fas fa-th-large',
                    'fas fa-directions' => 'fas fa-map-signs',
                    'fas fa-user-follow' => 'fas fa-user-plus',
                    'fas fa-note' => 'fas fa-sticky-note'
                ];
                
                if (isset($icon_map[$icon_class])) {
                    $icon_class = $icon_map[$icon_class];
                }
            } else {
                $icon_class = $icon;
            }
            
            // Create the card
            echo '<a href="' . base_url($url) . '" class="md3-module-card ' . $name . '">';
            echo '<i class="' . $icon_class . ' md3-module-icon"></i>';
            echo '<div class="md3-module-title">' . translate($name) . '</div>';
            if (!empty($desc)) {
                echo '<div class="md3-module-description">' . $desc . '</div>';
            }
            echo '</a>';
        }
        
        echo '</div>';
        ?>
    </div>
    
    <?php if ($is_mobile): ?>
    <div class="md3-bottom-nav">
        <a href="<?php echo base_url('dashboard'); ?>" class="md3-bottom-nav-item">
            <i class="fas fa-tachometer-alt md3-bottom-nav-icon"></i>
            <span class="md3-bottom-nav-label"><?php echo translate('dashboard'); ?></span>
        </a>
        <a href="<?php echo base_url('mainmenu'); ?>" class="md3-bottom-nav-item active">
            <i class="fas fa-th-large md3-bottom-nav-icon"></i>
            <span class="md3-bottom-nav-label"><?php echo translate('menu'); ?></span>
        </a>
        <?php if (is_student_loggedin() || (is_parent_loggedin() && !empty(get_activeChildren_id()))): ?>
        <a href="<?php echo base_url('userrole/attendance'); ?>" class="md3-bottom-nav-item">
            <i class="fas fa-check-double md3-bottom-nav-icon"></i>
            <span class="md3-bottom-nav-label"><?php echo translate('attendance'); ?></span>
        </a>
        <a href="<?php echo base_url('userrole/invoice'); ?>" class="md3-bottom-nav-item">
            <i class="fas fa-money-bill-wave md3-bottom-nav-icon"></i>
            <span class="md3-bottom-nav-label"><?php echo translate('fees'); ?></span>
        </a>
        <?php else: ?>
        <a href="<?php echo base_url('student'); ?>" class="md3-bottom-nav-item">
            <i class="fas fa-user-graduate md3-bottom-nav-icon"></i>
            <span class="md3-bottom-nav-label"><?php echo translate('student'); ?></span>
        </a>
        <a href="<?php echo base_url('settings/profile'); ?>" class="md3-bottom-nav-item">
            <i class="fas fa-user-cog md3-bottom-nav-icon"></i>
            <span class="md3-bottom-nav-label"><?php echo translate('profile'); ?></span>
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <script src="<?php echo base_url('assets/js/mobile-detector.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/main-menu-responsive.js'); ?>"></script>
    <script>
        // Add touch effect for mobile devices
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.md3-module-card');
            const navItems = document.querySelectorAll('.md3-bottom-nav-item');
            
            // Function to add touch effect
            function addTouchEffect(elements) {
                elements.forEach(element => {
                    element.addEventListener('touchstart', function() {
                        this.style.transform = 'scale(0.95)';
                        this.style.opacity = '0.9';
                    }, { passive: true });
                    
                    element.addEventListener('touchend', function() {
                        this.style.transform = '';
                        this.style.opacity = '';
                    }, { passive: true });
                    
                    element.addEventListener('touchcancel', function() {
                        this.style.transform = '';
                        this.style.opacity = '';
                    }, { passive: true });
                });
            }
            
            // Add effects
            addTouchEffect(cards);
            addTouchEffect(navItems);
        });
    </script>
</body>
</html><div class="row">
  <div class="col-md-12">
    <div class="panel">
      <div class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-th-large"></i> <?php echo translate('main_menu'); ?></h4>
      </div>
      <div class="panel-body">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/material-design-3.css'); ?>">
        <style>
          /* Hide mobile footer in web view */
          .mobile-footer {
            display: none !important;
          }
        </style>
        
        <?php
        // Display all menu items in a single grid
        echo '<div class="md3-module-grid">';
        
        // Loop through all menu items
        foreach ($menu_items as $item) {
            $name = $item['name'];
            $url = $item['url'];
            $icon = $item['icon'];
            $desc = $item['desc'];
            
            // Convert icon class if needed (for compatibility with both icon sets)
            if (strpos($icon, 'icons icon-') !== false) {
                $icon_class = str_replace('icons icon-', 'fas fa-', $icon);
                // Map some specific icons
                $icon_map = [
                    'fas fa-grid' => 'fas fa-th-large',
                    'fas fa-directions' => 'fas fa-map-signs',
                    'fas fa-user-follow' => 'fas fa-user-plus',
                    'fas fa-note' => 'fas fa-sticky-note'
                ];
                
                if (isset($icon_map[$icon_class])) {
                    $icon_class = $icon_map[$icon_class];
                }
            } else {
                $icon_class = $icon;
            }
            
            // Create the card
            echo '<a href="' . base_url($url) . '" class="md3-module-card ' . $name . '">';
            echo '<i class="' . $icon_class . ' md3-module-icon"></i>';
            echo '<div class="md3-module-title">' . translate($name) . '</div>';
            if (!empty($desc)) {
                echo '<div class="md3-module-description">' . $desc . '</div>';
            }
            echo '</a>';
        }
        
        echo '</div>';
        ?>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url('assets/js/main-menu-responsive.js'); ?>"></script><div class="row">
  <div class="col-md-12">
    <div class="panel">
      <div class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-th-large"></i> <?php echo translate('main_menu'); ?></h4>
      </div>
      <div class="panel-body">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/material-design-3.css'); ?>">
        
        <?php
        // Function to check if user has permission to access a module
        function has_module_permission($permissions, $module_name) {
          if (empty($permissions)) {
            return false;
          }
          
          foreach ($permissions as $permission) {
            if ($permission->permission_prefix == $module_name && $permission->is_view == '1') {
              return true;
            }
          }
          return false;
        }
        
        // Define module categories and their modules
        $module_categories = array(
          'Academic' => array('student', 'classes', 'subject', 'section', 'syllabus'),
          'Student Activities' => array('attendance', 'exam', 'mark', 'homework', 'promotion'),
          'Finance' => array('fees', 'expense', 'income', 'accounting'),
          'Resources' => array('library', 'inventory', 'hostel', 'transport'),
          'Communication' => array('event', 'communication', 'sendsmsmail'),
          'Administration' => array('settings', 'reports', 'dashboard', 'leave', 'award'),
        );
        
        // Define module icons and descriptions
        $module_details = array(
          'student' => array('icon' => 'fas fa-user-graduate', 'desc' => 'Manage student profiles, admissions, and records'),
          'classes' => array('icon' => 'fas fa-chalkboard', 'desc' => 'Manage classes, sections, and assignments'),
          'subject' => array('icon' => 'fas fa-book', 'desc' => 'Manage subjects and curriculum'),
          'section' => array('icon' => 'fas fa-puzzle-piece', 'desc' => 'Organize classes into sections'),
          'syllabus' => array('icon' => 'fas fa-list-alt', 'desc' => 'Manage course syllabi and content'),
          'attendance' => array('icon' => 'fas fa-check-double', 'desc' => 'Track student and staff attendance'),
          'exam' => array('icon' => 'fas fa-diagnoses', 'desc' => 'Manage exams, schedules, and halls'),
          'mark' => array('icon' => 'fas fa-poll', 'desc' => 'Record and manage student marks'),
          'homework' => array('icon' => 'fas fa-tasks', 'desc' => 'Assign and track homework'),
          'promotion' => array('icon' => 'fas fa-arrow-circle-up', 'desc' => 'Manage student promotions'),
          'fees' => array('icon' => 'fas fa-money-bill-wave', 'desc' => 'Manage student fees and payments'),
          'expense' => array('icon' => 'fas fa-minus-circle', 'desc' => 'Track and manage expenses'),
          'income' => array('icon' => 'fas fa-plus-circle', 'desc' => 'Record and manage income'),
          'accounting' => array('icon' => 'fas fa-calculator', 'desc' => 'Financial accounting and reports'),
          'library' => array('icon' => 'fas fa-book-reader', 'desc' => 'Manage library books and resources'),
          'inventory' => array('icon' => 'fas fa-boxes', 'desc' => 'Track school inventory and assets'),
          'hostel' => array('icon' => 'fas fa-hotel', 'desc' => 'Manage student hostels and rooms'),
          'transport' => array('icon' => 'fas fa-bus', 'desc' => 'Manage school transportation'),
          'event' => array('icon' => 'fas fa-calendar-alt', 'desc' => 'Manage school events and activities'),
          'communication' => array('icon' => 'fas fa-comments', 'desc' => 'School-wide communication tools'),
          'sendsmsmail' => array('icon' => 'fas fa-envelope', 'desc' => 'Send SMS and email notifications'),
          'settings' => array('icon' => 'fas fa-cogs', 'desc' => 'System settings and configuration'),
          'reports' => array('icon' => 'fas fa-chart-bar', 'desc' => 'Generate and view reports'),
          'dashboard' => array('icon' => 'fas fa-tachometer-alt', 'desc' => 'School performance dashboard'),
          'leave' => array('icon' => 'fas fa-sign-out-alt', 'desc' => 'Manage staff and student leaves'),
          'award' => array('icon' => 'fas fa-trophy', 'desc' => 'Manage awards and recognitions')
        );
        
        // Display modules by category
        foreach ($module_categories as $category => $category_modules) {
          $has_modules = false;
          
          // Check if user has access to any module in this category
          foreach ($category_modules as $module) {
            if (has_module_permission($permissions, $module)) {
              $has_modules = true;
              break;
            }
          }
          
          // Only display category if user has access to at least one module
          if ($has_modules) {
            echo '<h3 class="md3-category-title">' . translate($category) . '</h3>';
            echo '<div class="md3-module-grid">';
            
            foreach ($category_modules as $module) {
              if (has_module_permission($permissions, $module)) {
                $icon = isset($module_details[$module]['icon']) ? $module_details[$module]['icon'] : 'fas fa-cube';
                $desc = isset($module_details[$module]['desc']) ? $module_details[$module]['desc'] : '';
                
                echo '<a href="' . base_url($module) . '" class="md3-module-card ' . $module . '">';
                echo '<i class="' . $icon . ' md3-module-icon"></i>';
                echo '<div class="md3-module-title">' . translate($module) . '</div>';
                if (!empty($desc)) {
                  echo '<div class="md3-module-description">' . $desc . '</div>';
                }
                echo '</a>';
              }
            }
            
            echo '</div>';
          }
        }
        ?>
      </div>
    </div>
  </div>
</div>