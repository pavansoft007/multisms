<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="keywords" content="">
    <meta name="description" content="Bigwala Technologies school management system">
    <meta name="author" content="Bigwala Technologies">
    <title><?php echo translate('login');?></title>
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.png');?>">
    
    <!-- Web Fonts  -->
    <link href="<?php echo is_secure('fonts.googleapis.com/css?family=Signika:300,400,600,700');?>" rel="stylesheet"> 
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap/css/bootstrap.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/font-awesome/css/all.min.css'); ?>">
    <script src="<?php echo base_url('assets/vendor/jquery/jquery.js');?>"></script>
    
    <!-- sweetalert js/css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/sweetalert/sweetalert-custom.css');?>">
    <script src="<?php echo base_url('assets/vendor/sweetalert/sweetalert.min.js');?>"></script>
    
    <!-- Mobile login page style -->
    <style>
        body {
            font-family: 'Signika', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .mobile-login-container {
            padding: 20px;
            max-width: 100%;
            margin: 0 auto;
        }
        .mobile-login-logo {
            text-align: center;
            margin-bottom: 30px;
            padding-top: 40px;
        }
        .mobile-login-logo img {
            max-width: 150px;
            height: auto;
        }
        .mobile-login-logo h2 {
            margin-top: 15px;
            font-size: 22px;
            font-weight: 600;
            color: #3A3978;
        }
        .mobile-login-form {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .mobile-login-form h3 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 18px;
            text-align: center;
            color: #3A3978;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .input-group {
            width: 100%;
            position: relative;
        }
        .input-group-addon {
            position: absolute;
            left: 0;
            top: 0;
            width: 40px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-right: 1px solid #ddd;
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }
        .input-group input {
            padding-left: 50px;
            height: 45px;
            border-radius: 4px;
        }
        .btn-login {
            background: #3A3978;
            color: #fff;
            border: none;
            height: 45px;
            border-radius: 4px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
        }
        .forgot-text {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 15px;
        }
        .forgot-text a {
            color: #3A3978;
        }
        .mobile-login-footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
        .social-links {
            text-align: center;
            margin-top: 20px;
        }
        .social-links a {
            display: inline-block;
            width: 35px;
            height: 35px;
            line-height: 35px;
            text-align: center;
            background: #f1f1f1;
            border-radius: 50%;
            margin: 0 5px;
            color: #3A3978;
        }
        .error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
    </style>
    
    <script type="text/javascript">
        var base_url = '<?php echo base_url() ?>';
    </script>
</head>
<body>
    <div class="mobile-login-container">
        <div class="mobile-login-logo">
            <img src="<?php echo base_url('uploads/app_image/system_logo/'.$global_images['system_logo'].'');?>" alt="Logo">
            <h2><?php echo $global_config['institute_name'];?></h2>
        </div>
        
        <div class="mobile-login-form">
            <h3><?php echo translate('login_to_your_account');?></h3>
            
            <?php echo form_open($this->uri->uri_string()); ?>
                <div class="form-group <?php if (form_error('email')) echo 'has-error'; ?>">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="far fa-user"></i>
                        </span>
                        <input type="text" class="form-control" name="email" value="<?php echo set_value('email');?>" placeholder="<?php echo translate('email');?>" />
                    </div>
                    <span class="error"><?php echo form_error('email'); ?></span>
                </div>
                
                <div class="form-group <?php if (form_error('password')) echo 'has-error'; ?>">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fas fa-unlock-alt"></i>
                        </span>
                        <input type="password" class="form-control" name="password" placeholder="<?php echo translate('password');?>" />
                    </div>
                    <span class="error"><?php echo form_error('password'); ?></span>
                </div>
                
                <div class="forgot-text">
                    <div class="checkbox-replace">
                        <label class="i-checks">
                            <input type="checkbox" name="remember" id="remember">
                            <i></i> <?php echo translate('remember');?>
                        </label>
                    </div>
                    <div>
                        <a href="<?php echo base_url('authentication/forgot');?>"><?php echo translate('lose_your_password');?></a>
                    </div>
                </div>
                
                <button type="submit" id="btn_submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt"></i> <?php echo translate('login');?>
                </button>
            <?php echo form_close();?>
        </div>
        
        <div class="social-links">
            <a href="<?php echo $global_config['facebook_url'];?>" target="_blank">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="<?php echo $global_config['twitter_url'];?>" target="_blank">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="<?php echo $global_config['linkedin_url'];?>" target="_blank">
                <i class="fab fa-linkedin-in"></i>
            </a>
            <a href="<?php echo $global_config['youtube_url'];?>" target="_blank">
                <i class="fab fa-youtube"></i>
            </a>
        </div>
        
        <div class="mobile-login-footer">
            <p><?php echo $global_config['footer_text'];?></p>
        </div>
    </div>
    
    <script src="<?php echo base_url('assets/vendor/bootstrap/js/bootstrap.js');?>"></script>
    <script src="<?php echo base_url('assets/vendor/jquery-placeholder/jquery-placeholder.js');?>"></script>

    <?php
    $alertclass = "";
    if($this->session->flashdata('alert-message-success')){
        $alertclass = "success";
    } else if ($this->session->flashdata('alert-message-error')){
        $alertclass = "error";
    } else if ($this->session->flashdata('alert-message-info')){
        $alertclass = "info";
    }
    if($alertclass != ''):
        $alert_message = $this->session->flashdata('alert-message-'. $alertclass);
    ?>
        <script type="text/javascript">
            swal({
                toast: true,
                position: 'top-end',
                type: '<?php echo $alertclass;?>',
                title: '<?php echo $alert_message;?>',
                confirmButtonClass: 'btn btn-default',
                buttonsStyling: false,
                timer: 8000
            })
        </script>
    <?php endif; ?>
</body>
</html>