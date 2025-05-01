<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Mobile Dashboard</title>
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.png');?>">
    <!-- Material Design Fonts -->
    <link href="<?php echo is_secure('fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');?>" rel="stylesheet">
    <!-- Material Icons -->
    <link href="<?php echo is_secure('fonts.googleapis.com/icon?family=Material+Icons');?>" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap/css/bootstrap.css');?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/font-awesome/css/all.min.css');?>">
    <!-- Mobile Cards CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/mobile-cards.css');?>?v=<?php echo time(); ?>">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5; /* Light gray background */
            margin: 0;
            padding: 0;
            padding-bottom: 70px; /* Add padding to prevent content from being hidden by the bottom nav */
        }
        .mobile-header {
            background-color: #0091cd;
            color: white;
            padding: 16px;
            text-align: center;
            position: relative;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .mobile-header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 500;
        }
        .mobile-header .profile-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
        }
        .mobile-header .back-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
        }
        .mobile-welcome {
            padding: 20px;
            background-color: white;
            margin-bottom: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .mobile-welcome h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 500;
            color: #333333;
        }
        .mobile-welcome p {
            margin: 8px 0 0;
            font-size: 14px;
            color: #666;
        }
        /* Override mobile-cards-grid styles */
        .mobile-cards-grid {
            padding: 20px;
        }
        
        /* No cards message */
        .no-cards {
            text-align: center;
            padding: 40px 20px;
            color: #757575;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 16px;
        }
        .no-cards .material-icons {
            font-size: 48px;
            margin-bottom: 16px;
            color: #bbbbbb;
        }
        
        /* Bottom navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            display: flex;
            background: #ffffff;
            box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .bottom-nav a {
            flex: 1;
            padding: 12px 0;
            background: none;
            border: none;
            font-size: 14px;
            color: #757575;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: color 0.3s;
            text-decoration: none;
        }
        .bottom-nav a.active {
            color: #0091cd;
        }
        .bottom-nav a:hover {
            color: #0091cd;
        }
        .material-icons {
            font-size: 24px;
            margin-bottom: 4px;
        }
    </style>
</head>
<body data-role-id="<?php echo $role_id; ?>">
    <div class="mobile-header">
        <a href="<?php echo base_url('dashboard'); ?>" class="back-icon text-white">
            <i class="material-icons">arrow_back</i>
        </a>
        <h1>Mobile Dashboard</h1>
        <a href="<?php echo base_url('profile'); ?>" class="profile-icon text-white">
            <i class="material-icons">person</i>
        </a>
    </div>
    
    <div class="mobile-welcome">
        <h2>Welcome, <?php echo $this->session->userdata('name'); ?></h2>
        <p>Access your most important features below</p>
    </div>
    
    <?php if (!empty($cards)): ?>
    <div class="mobile-cards-grid">
        <?php foreach ($cards as $card): ?>
        <a href="<?php echo $card['url']; ?>" class="mobile-card <?php echo $card['color']; ?>">
            <i class="material-icons card-icon"><?php echo $card['icon']; ?></i>
            <h3 class="card-title"><?php echo $card['title']; ?></h3>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="no-cards">
        <span class="material-icons">dashboard_customize</span>
        <p>No cards configured for your role. Please contact the administrator.</p>
    </div>
    <?php endif; ?>
    
    <div class="bottom-nav">
        <a href="<?php echo base_url('mobilemain'); ?>" class="active">
            <span class="material-icons">dashboard</span>
            <div>Dashboard</div>
        </a>
        <a href="<?php echo base_url('attendance'); ?>">
            <span class="material-icons">how_to_reg</span>
            <div>Attendance</div>
        </a>
        <a href="<?php echo base_url('fees'); ?>">
            <span class="material-icons">attach_money</span>
            <div>Fees</div>
        </a>
        <a href="<?php echo base_url('mobilemain'); ?>" id="mobile-more-menu">
            <span class="material-icons">menu</span>
            <div>More</div>
        </a>
    </div>
    
    <!-- jQuery -->
    <script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js');?>"></script>
    <!-- Bootstrap JS -->
    <script src="<?php echo base_url('assets/vendor/bootstrap/js/bootstrap.js');?>"></script>
    <!-- Custom JS -->
    <script>
        var base_url = '<?php echo base_url(); ?>';
    </script>
    <script src="<?php echo base_url('assets/js/mobile-footer-update.js');?>?v=<?php echo time(); ?>"></script>
</body>
</html>
</body>
</html>