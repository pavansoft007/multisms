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