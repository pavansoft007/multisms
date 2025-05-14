<section class="panel appear-animation" data-appear-animation="<?php echo $global_config['animations']; ?>" data-appear-animation-delay="100">
	<div class="panel-heading">
		<h4 class="panel-title">
			<i class="fas fa-columns"></i> <?=translate('sidebar_settings')?>
		</h4>
	</div>
	<div class="panel-body">
		<?php
			echo form_open(base_url('settings/sidebar'), array(
				'method'	=> 'post',
				'class' 	=> 'validate form-horizontal form-bordered'
			));
		?>
		<div class="form-group">
			<label class="col-md-2 control-label">Sidebar Color</label>
			<div class="col-md-8">
				<ul class="list-unstyled thememenu-sy">
					<li>
						<div class="theme-box">
							<label> 
								<input name="sidebar_color" value="default" type="radio" <?=(isset($theme_config['sidebar_color']) && $theme_config['sidebar_color'] == 'default' ? 'checked' : (!isset($theme_config['sidebar_color']) ? 'checked' : ''));?>>
								<div class="theme-img" style="background-color: #FFF; height: 80px; display: flex; align-items: center; justify-content: center; color: #333; font-weight: bold;">
									Default White
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="sidebar_color" value="blue" type="radio" <?=(isset($theme_config['sidebar_color']) && $theme_config['sidebar_color'] == 'blue' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color: #3A3978; height: 80px; display: flex; align-items: center; justify-content: center; color: #FFF; font-weight: bold;">
									Blue
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="sidebar_color" value="green" type="radio" <?=(isset($theme_config['sidebar_color']) && $theme_config['sidebar_color'] == 'green' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color: #28a745; height: 80px; display: flex; align-items: center; justify-content: center; color: #FFF; font-weight: bold;">
									Green
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="sidebar_color" value="purple" type="radio" <?=(isset($theme_config['sidebar_color']) && $theme_config['sidebar_color'] == 'purple' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color:rgb(237, 233, 246); height: 80px; display: flex; align-items: center; justify-content: center; color: #FFF; font-weight: bold;">
									Purple
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="sidebar_color" value="red" type="radio" <?=(isset($theme_config['sidebar_color']) && $theme_config['sidebar_color'] == 'red' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color: #dc3545; height: 80px; display: flex; align-items: center; justify-content: center; color: #FFF; font-weight: bold;">
									Red
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="sidebar_color" value="dark" type="radio" <?=(isset($theme_config['sidebar_color']) && $theme_config['sidebar_color'] == 'dark' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color: #343a40; height: 80px; display: flex; align-items: center; justify-content: center; color: #FFF; font-weight: bold;">
									Dark
								</div>
							</label>
						</div>
					</li>
				</ul>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-md-2 control-label">Sidebar Text Color</label>
			<div class="col-md-8">
				<ul class="list-unstyled thememenu-sy">
					<li>
						<div class="theme-box">
							<label> 
								<input name="sidebar_text_color" value="light" type="radio" <?=(isset($theme_config['sidebar_text_color']) && $theme_config['sidebar_text_color'] == 'light' ? 'checked' : (!isset($theme_config['sidebar_text_color']) ? 'checked' : ''));?>>
								<div class="theme-img" style="background-color: #333; height: 80px; display: flex; align-items: center; justify-content: center; color: #FFF; font-weight: bold;">
									Light Text
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="sidebar_text_color" value="dark" type="radio" <?=(isset($theme_config['sidebar_text_color']) && $theme_config['sidebar_text_color'] == 'dark' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color: #f8f9fa; height: 80px; display: flex; align-items: center; justify-content: center; color: #333; font-weight: bold;">
									Dark Text
								</div>
							</label>
						</div>
					</li>
				</ul>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-md-2 control-label">Menu Text Color</label>
			<div class="col-md-8">
				<ul class="list-unstyled thememenu-sy">
					<li>
						<div class="theme-box">
							<label> 
								<input name="menu_text_color" value="light" type="radio" <?=(isset($theme_config['menu_text_color']) && $theme_config['menu_text_color'] == 'light' ? 'checked' : (!isset($theme_config['menu_text_color']) ? 'checked' : ''));?>>
								<div class="theme-img" style="background-color: #333; height: 80px; display: flex; align-items: center; justify-content: center; color: #FFF; font-weight: bold;">
									Light Text
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="menu_text_color" value="dark" type="radio" <?=(isset($theme_config['menu_text_color']) && $theme_config['menu_text_color'] == 'dark' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color: #f8f9fa; height: 80px; display: flex; align-items: center; justify-content: center; color: #333; font-weight: bold;">
									Dark Text
								</div>
							</label>
						</div>
					</li>
				</ul>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-2 control-label">Menu Background Color</label>
			<div class="col-md-8">
				<ul class="list-unstyled thememenu-sy">
					<li>
						<div class="theme-box">
							<label> 
								<input name="menu_bg_color" value="default" type="radio" <?=(isset($theme_config['menu_bg_color']) && $theme_config['menu_bg_color'] == 'default' ? 'checked' : (!isset($theme_config['menu_bg_color']) ? 'checked' : ''));?>>
								<div class="theme-img" style="background-color: transparent; height: 80px; display: flex; align-items: center; justify-content: center; color: #333; font-weight: bold; border: 1px solid #ddd;">
									Default (Transparent)
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="menu_bg_color" value="light" type="radio" <?=(isset($theme_config['menu_bg_color']) && $theme_config['menu_bg_color'] == 'light' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color: #f8f9fa; height: 80px; display: flex; align-items: center; justify-content: center; color: #333; font-weight: bold;">
									Light
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="menu_bg_color" value="dark" type="radio" <?=(isset($theme_config['menu_bg_color']) && $theme_config['menu_bg_color'] == 'dark' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color: #343a40; height: 80px; display: flex; align-items: center; justify-content: center; color: #FFF; font-weight: bold;">
									Dark
								</div>
							</label>
						</div>
					</li>
				</ul>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-2 control-label">Active Menu Text Color</label>
			<div class="col-md-8">
				<ul class="list-unstyled thememenu-sy">
					<li>
						<div class="theme-box">
							<label> 
								<input name="active_menu_text_color" value="light" type="radio" <?=(isset($theme_config['active_menu_text_color']) && $theme_config['active_menu_text_color'] == 'light' ? 'checked' : (!isset($theme_config['active_menu_text_color']) ? 'checked' : ''));?>>
								<div class="theme-img" style="background-color: #333; height: 80px; display: flex; align-items: center; justify-content: center; color: #FFF; font-weight: bold;">
									Light Text
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="active_menu_text_color" value="dark" type="radio" <?=(isset($theme_config['active_menu_text_color']) && $theme_config['active_menu_text_color'] == 'dark' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color: #f8f9fa; height: 80px; display: flex; align-items: center; justify-content: center; color: #333; font-weight: bold;">
									Dark Text
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="active_menu_text_color" value="primary" type="radio" <?=(isset($theme_config['active_menu_text_color']) && $theme_config['active_menu_text_color'] == 'primary' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color: #f8f9fa; height: 80px; display: flex; align-items: center; justify-content: center; color: #007bff; font-weight: bold;">
									Primary (Blue)
								</div>
							</label>
						</div>
					</li>
				</ul>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-2 control-label">Active Menu Background</label>
			<div class="col-md-8">
				<ul class="list-unstyled thememenu-sy">
					<li>
						<div class="theme-box">
							<label> 
								<input name="active_menu_bg" value="default" type="radio" <?=(isset($theme_config['active_menu_bg']) && $theme_config['active_menu_bg'] == 'default' ? 'checked' : (!isset($theme_config['active_menu_bg']) ? 'checked' : ''));?>>
								<div class="theme-img" style="background-color: transparent; height: 80px; display: flex; align-items: center; justify-content: center; color: #333; font-weight: bold; border: 1px solid #ddd;">
									Default (Transparent)
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="active_menu_bg" value="light" type="radio" <?=(isset($theme_config['active_menu_bg']) && $theme_config['active_menu_bg'] == 'light' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color: #f8f9fa; height: 80px; display: flex; align-items: center; justify-content: center; color: #333; font-weight: bold;">
									Light
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="active_menu_bg" value="primary" type="radio" <?=(isset($theme_config['active_menu_bg']) && $theme_config['active_menu_bg'] == 'primary' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color: #007bff; height: 80px; display: flex; align-items: center; justify-content: center; color: #FFF; font-weight: bold;">
									Primary (Blue)
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="active_menu_bg" value="dark" type="radio" <?=(isset($theme_config['active_menu_bg']) && $theme_config['active_menu_bg'] == 'dark' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color: #343a40; height: 80px; display: flex; align-items: center; justify-content: center; color: #FFF; font-weight: bold;">
									Dark
								</div>
							</label>
						</div>
					</li>
				</ul>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-2 control-label">Menu Hover Style</label>
			<div class="col-md-8">
				<ul class="list-unstyled thememenu-sy">
					<li>
						<div class="theme-box">
							<label> 
								<input name="menu_hover_style" value="default" type="radio" <?=(isset($theme_config['menu_hover_style']) && $theme_config['menu_hover_style'] == 'default' ? 'checked' : (!isset($theme_config['menu_hover_style']) ? 'checked' : ''));?>>
								<div class="theme-img" style="background-color: #f8f9fa; height: 80px; display: flex; align-items: center; justify-content: center; color: #333; font-weight: bold;">
									Default
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="menu_hover_style" value="light" type="radio" <?=(isset($theme_config['menu_hover_style']) && $theme_config['menu_hover_style'] == 'light' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color: #f8f9fa; height: 80px; display: flex; align-items: center; justify-content: center; color: #333; font-weight: bold; border: 1px solid #ddd;">
									Light Background
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="menu_hover_style" value="dark" type="radio" <?=(isset($theme_config['menu_hover_style']) && $theme_config['menu_hover_style'] == 'dark' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color: #343a40; height: 80px; display: flex; align-items: center; justify-content: center; color: #FFF; font-weight: bold;">
									Dark Background
								</div>
							</label>
						</div>
					</li>
					<li>
						<div class="theme-box">
							<label> 
								<input name="menu_hover_style" value="primary" type="radio" <?=(isset($theme_config['menu_hover_style']) && $theme_config['menu_hover_style'] == 'primary' ? 'checked' : '');?>>
								<div class="theme-img" style="background-color: #007bff; height: 80px; display: flex; align-items: center; justify-content: center; color: #FFF; font-weight: bold;">
									Primary Background
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
					<button type="submit" class="btn btn btn-default btn-block" name="submit" value="sidebar">
						<i class="fas fa-plus-circle"></i> <?=translate('save');?>
					</button>
				</div>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</div>
</section>