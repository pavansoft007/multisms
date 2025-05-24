<!doctype html>
<?php
// Ensure $theme_config is set before accessing its keys
$theme_config = isset($theme_config) ? $theme_config : [];

// Use CodeIgniter's singleton to access the loader and session
$CI = get_instance();
?>

<html class="fixed sidebar-left-sm <?php echo (isset($theme_config['dark_skin']) && $theme_config['dark_skin'] == 'true' ? 'dark' : 'sidebar-light');?>">
<!-- html header -->
<?php $CI->load->view('layout/header.php');?>

<body class="loading-overlay-showing <?php 
	echo isset($theme_config['menu_text_color']) ? 'menu-text-' . $theme_config['menu_text_color'] : ''; 
	echo isset($theme_config['menu_bg_color']) && $theme_config['menu_bg_color'] != 'default' ? ' menu-bg-' . $theme_config['menu_bg_color'] : '';
	echo isset($theme_config['active_menu_text_color']) ? ' active-menu-text-' . $theme_config['active_menu_text_color'] : '';
	echo isset($theme_config['active_menu_bg']) && $theme_config['active_menu_bg'] != 'default' ? ' active-menu-bg-' . $theme_config['active_menu_bg'] : '';
	echo isset($theme_config['menu_hover_style']) && $theme_config['menu_hover_style'] != 'default' ? ' menu-hover-' . $theme_config['menu_hover_style'] : '';
?>" data-loading-overlay>
	<!-- page preloader -->
	<div class="loading-overlay dark">
		<div class="ring-loader">
			Loading <span></span>
		</div>
	</div>
	<section class="body">
		<!-- top navbar-->
		<?php $CI->load->view('layout/topbar.php');?>
		<div class="inner-wrapper">
			<!-- sidebar -->
			<?php 
			if (is_student_loggedin() || is_parent_loggedin()) {
				$CI->load->view('userrole/sidebar'); 
			} else {
				$CI->load->view('layout/sidebar'); 
			} 
			?>
			<!-- page main content -->
			<section role="main" class="content-body">
				<header class="page-header">
					<a class="page-title-icon" href="<?php echo base_url('dashboard');?>"><i class="fas fa-home"></i></a>
					<h2><?php echo $title;?></h2>
				</header>
				<?php 
// Get the CI instance
$CI = &get_instance();
// Extract all variables from $CI->data to make them available in the view
if (isset($CI->data) && is_array($CI->data)) {
    extract($CI->data);
}
$CI->load->view($sub_page); 
?>
			</section>
		</div>
	</section>

	<!-- JS Scripts -->
	<?php $CI->load->view('layout/script.php');?>
	
	<?php
	$alertclass = "";
	if($CI->session->flashdata('alert-message-success')){
		$alertclass = "success";
	} else if ($CI->session->flashdata('alert-message-error')){
		$alertclass = "error";
	} else if ($CI->session->flashdata('alert-message-info')){
		$alertclass = "info";
	}
	if($alertclass != ''):
		$alert_message = $CI->session->flashdata('alert-message-'. $alertclass);
	?>
		<script type="text/javascript">
			swal({
				toast: true,
				position: 'top-end',
				type: '<?php echo $alertclass?>',
				title: '<?php echo $alert_message?>',
				confirmButtonClass: 'btn btn-default',
				buttonsStyling: false,
				timer: 8000
			})
		</script>
	<?php endif; ?>

	<!-- sweetalert box -->
	<script type="text/javascript">
		function confirm_modal(delete_url) {
			swal({
				title: "<?php echo translate('are_you_sure')?>",
				text: "<?php echo translate('delete_this_information')?>",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn btn-default swal2-btn-default",
				cancelButtonClass: "btn btn-default swal2-btn-default",
				confirmButtonText: "<?php echo translate('yes_continue')?>",
				cancelButtonText: "<?php echo translate('cancel')?>",
				buttonsStyling: false,
				footer: "<?php echo translate('deleted_note')?>"
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: delete_url,
						type: "POST",
						success:function(data) {
							swal({
							title: "<?php echo translate('deleted')?>",
							text: "<?php echo translate('information_deleted')?>",
							buttonsStyling: false,
							showCloseButton: true,
							focusConfirm: false,
							confirmButtonClass: "btn btn-default swal2-btn-default",
							type: "success"
							}).then((result) => {
								if (result.value) {
									location.reload();
								}
							});
						}
					});
				}
			});
		}
	</script>
	<?php include(APPPATH . 'views/layout/footer.php'); ?>
</body>
</html>