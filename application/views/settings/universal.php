<section class="panel appear-animation" data-appear-animation="<?php echo $global_config['animations']; ?>" data-appear-animation-delay="100">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li <?=(empty($this->session->flashdata('active')) ? 'class="active"' : '');?>>
				<a href="#setting" data-toggle="tab">
					<i class="fas fa-chalkboard-teacher"></i> 
				   <span class="hidden-xs"> <?=translate('general_settings')?></span>
				</a>
			
			</li>
			<li <?=($this->session->flashdata('active') == 2 ? 'class="active"' : '');?>>
				<a href="#theme" data-toggle="tab">
				   <i class="fas fa-paint-roller"></i>
				   <span class="hidden-xs"> <?=translate('theme_settings')?></span>
				</a>
			
			</li>
			<li <?=($this->session->flashdata('active') == 3 ? 'class="active"' : '');?>>
				<a href="#upload" data-toggle="tab">
				   <i class="fab fa-uikit"></i>
				   <span class="hidden-xs"> <?=translate('logo')?></span>
				</a>
			</li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane box <?=(empty($this->session->flashdata('active')) ? 'active' : '');?>" id="setting">
				<?php echo form_open($this->uri->uri_string(), array( 'class' 	=> 'validate form-horizontal form-bordered' )); ?>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('institute_name')?></label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="institute_name" value="<?=set_value('institute_name', $global_config['institute_name'])?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('institution_code')?></label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="institution_code" value="<?=set_value('institution_code', $global_config['institution_code'])?>" />
						<div class="checkbox-replace mt-md">
							<label class="i-checks">
								<input type="checkbox" name="reg_prefix" id="reg_prefix" <?=($global_config['reg_prefix'] == 'on' ? 'checked' : '');?>>
								<i></i> The Institute Code will be used as the prefix for Student (Registration No).
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('mobile_no');?></label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="mobileno" value="<?=set_value('mobileno', $global_config['mobileno'])?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('address');?></label>
					<div class="col-md-6">
						<textarea name="address" rows="2" class="form-control" aria-required="true"><?=set_value('address', $global_config['address'])?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">
						<?=translate('currency');?>
					</label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="currency" value="INR" readonly/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('currency_symbol');?></label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="currency_symbol" value="₹" readonly/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('language');?></label>
					<div class="col-md-6">
						<?php
						$languages = $this->db->select('id,lang_field,name')->where('status', 1)->get('language_list')->result();
						foreach ($languages as $lang) {
							$array[$lang->lang_field] = ucfirst($lang->name);
						}
						echo form_dropdown("translation", $array, set_value('translation', $global_config['translation']), "class='form-control' data-plugin-selectTwo 
							data-width='100%' data-minimum-results-for-search='Infinity' ");
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('academic_session');?></label>
					<div class="col-md-6">
						<?php
						$arrayYear = array("" => translate('select'));
						$years = $this->db->get('schoolyear')->result();
						foreach ($years as $year) {
							$arrayYear[$year->id] = $year->school_year;
						}
						echo form_dropdown("session_id", $arrayYear, set_value('session_id', $global_config['session_id']), "class='form-control' required
						data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('timezone');?></label>
					<div class="col-md-6">
						<?php
						$timezones = $this->app_lib->timezone_list();
						echo form_dropdown("timezone", $timezones, set_value('timezone', $global_config['timezone']), "class='form-control populate' required id='timezones' 
						data-plugin-selectTwo data-width='100%'");
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">
						<?=translate('animations');?>
					</label>
					<div class="col-md-6">
						<?php
						$getAnimationslist = $this->app_lib->getAnimationslist();
						echo form_dropdown("animations", $getAnimationslist, set_value('animations', $global_config['animations']), "class='form-control populate' required
						id='timezones' data-plugin-selectTwo data-width='100%'");
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('date_format')?></label>
					<div class="col-md-6">
						<?php
						$getDateformat = $this->app_lib->getDateformat();
						echo form_dropdown("date_format", $getDateformat, set_value('date_format', $global_config['date_format']), "class='form-control' id='date_format' 
						data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
						?>
					</div>
				</div>
				<?php if((is_master_loggedin()) || (is_superadmin_loggedin())){ ?>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('footer_text');?></label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="footer_text" value="<?=set_value('footer_text', $global_config['footer_text'])?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Facebook URL</label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="facebook_url" value="<?=set_value('facebook_url', $global_config['facebook_url'])?>" />
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-md-3 control-label">Twitter URL</label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="twitter_url" value="<?=set_value('twitter_url', $global_config['twitter_url'])?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Linkedin URL</label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="linkedin_url" value="<?=set_value('linkedin_url', $global_config['linkedin_url'])?>" />
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-md-3 control-label">Youtube URL</label>
					<div class="col-md-6 mb-md">
						<input type="text" class="form-control" name="youtube_url" value="<?=set_value('youtube_url', $global_config['youtube_url'])?>" />
					</div>
				</div>
				<?php } ?>
				<footer class="panel-footer mt-lg">
					<div class="row">
						<div class="col-md-2 col-sm-offset-3">
							<button type="submit" class="btn btn btn-default btn-block" name="submit" value="setting">
								<i class="fas fa-plus-circle"></i> <?=translate('save');?>
							</button>
						</div>
					</div>
				</footer>
				<?php echo form_close(); ?>
			</div>

			<div class="tab-pane box <?=($this->session->flashdata('active') == 2 ? 'active' : '');?>" id="theme">
				<?php
					echo form_open($this->uri->uri_string(), array(
						'method'	=> 'post',
						'class' 	=> 'validate form-horizontal form-bordered'
					));
				?>
				<div class="form-group">
					<label class="col-md-2 control-label" for="zoomcontrol">Theme</label>
					<div class="col-md-8">
						<ul class="list-unstyled thememenu-sy">
							<li>
								<div class="theme-box">
									<label> 
										<input name="dark_skin" value="false" type="radio" <?=($theme_config['dark_skin'] == 'false' ? 'checked' : '');?>>
										<div class="theme-img">
											<img src="<?=base_url('assets/images/theme/light.png')?>">
										</div>
									</label>
								</div>
							</li>
							<li>
								<div class="theme-box">
									<label >
										<input name="dark_skin" value="true" type="radio" <?=($theme_config['dark_skin'] == 'true' ? 'checked' : '');?> >
										<div class="theme-img">
											<img src="<?=base_url('assets/images/theme/dark.png')?>">
										</div>
									</label>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label" for="zoomcontrol">Border</label>
					<div class="col-md-8 mb-md">
						<ul class="list-unstyled thememenu-sy">
							<li>
								<div class="theme-box">
									<label> 
										<input name="border_mode" value="true" type="radio" <?=($theme_config['border_mode'] == 'true' ? 'checked' : '')?> >
										<div class="theme-img">
											<img src="<?=base_url('assets/images/theme/rounded.png')?>">
										</div>
									</label>
								</div>
							</li>
							<li>
								<div class="theme-box">
									<label >
										<input name="border_mode" value="false" type="radio" <?=($theme_config['border_mode'] == 'false' ? 'checked' : '')?> >
										<div class="theme-img">
											<img src="<?=base_url('assets/images/theme/square.png')?>">
										</div>
									</label>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-2 col-sm-offset-3">
							<button type="submit" class="btn btn btn-default btn-block" name="submit" value="theme">
								<i class="fas fa-plus-circle"></i> <?=translate('save');?>
							</button>
						</div>
					</div>
				</footer>
				<?php echo form_close(); ?>
			</div>
			<div class="tab-pane box <?=($this->session->flashdata('active') == 3 ? 'active' : '');?>" id="upload">
				<?php
					echo form_open_multipart($this->uri->uri_string(), array('class' 	=> 'validate'));
				?>

				<!-- all logo -->
				<div class="headers-line">
					<i class="fab fa-envira"></i> <?=translate('logo');?>
				</div>
				<div class="row">
					<?php if(is_master_loggedin() || is_superadmin_loggedin()){ ?>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label"><?=translate('system_logo');?></label>
							<input type="file" name="system_logo" class="dropify" data-allowed-file-extensions="png" data-default-file="<?=($global_images['branch_id']==''||$global_images['system_logo']=='logo.png')?base_url('uploads/app_image/logo.png'):base_url('uploads/app_image/system_logo/'.$global_images['system_logo'].'')?>" />
							<input type="hidden" name="old_system_logo" value="<?php echo $global_images['system_logo']; ?>" />
						</div>
					</div>
					<?php } ?>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label"><?=translate('text_logo');?></label>
							<input type="file" name="text_logo" class="dropify" data-allowed-file-extensions="png" data-default-file="<?=($global_images['branch_id']==''||$global_images['text_logo']=='logo-small.png')?base_url('uploads/app_image/logo-small.png'):base_url('uploads/app_image/text_logo/'.$global_images['text_logo'].'')?>" />
							<input type="hidden" name="old_text_logo" value="<?php echo $global_images['text_logo']; ?>" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label"><?=translate('printing_logo');?></label>
							<input type="file" name="print_file" class="dropify" data-allowed-file-extensions="png" data-default-file="<?=($global_images['branch_id']==''||$global_images['printing_logo']=='printing-logo.png')?base_url('uploads/app_image/printing-logo.png'):base_url('uploads/app_image/printing_logo/'.$global_images['printing_logo'].'')?>" />
							<input type="hidden" name="old_printing_logo" value="<?php echo $global_images['printing_logo']; ?>" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label"><?=translate('report_card');?></label>
							<input type="file" name="report_card" class="dropify" data-allowed-file-extensions="png" data-default-file="<?=($global_images['branch_id']=='' || $global_images['report_logo'] == 'report-card-logo.png')?base_url('uploads/app_image/report-card-logo.png'):base_url('uploads/app_image/report_logo/'.$global_images['report_logo'].'')?>" />
							<input type="hidden" name="old_report_logo" value="<?php echo $global_images['report_logo']; ?>" />
						</div>
					</div>
				</div>

				<!-- login background -->
				<?php if(is_master_loggedin() || is_superadmin_loggedin()){ ?>
				<div class="headers-line mt-lg">
					<i class="fas fa-sign-out-alt"></i> Login Background
				</div>
				<div class="row mb-ld">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Slider 1</label>
							<input type="file" name="slider_1" class="dropify" data-allowed-file-extensions="jpg" data-default-file="<?=base_url('uploads/login_image/slider_1.jpg')?>" />
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Slider 2</label>
							<input type="file" name="slider_2" class="dropify" data-allowed-file-extensions="jpg" data-default-file="<?=base_url('uploads/login_image/slider_2.jpg')?>" />
						</div>
					</div>
					<div class="col-md-4 mb-lg">
						<div class="form-group">
							<label class="control-label">Slider 3</label>
							<input type="file" name="slider_3" class="dropify" data-allowed-file-extensions="jpg" data-default-file="<?=base_url('uploads/login_image/slider_3.jpg')?>" />
						</div>
					</div>
				</div>
				<?php } ?>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-2 col-sm-offset-10">
							<button type="submit" class="btn btn btn-default btn-block" name="submit" value="logo">
								<i class="fas fa-upload"></i> <?=translate('upload')?>
							</button>
						</div>
					</div>
				</footer>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>