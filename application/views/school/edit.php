<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?=base_url('school')?>"><i class="fas fa-list-ul"></i> <?=translate('school_list')?></a>
			</li>
			<li class="active">
				<a href="#edit" data-toggle="tab"><i class="far fa-edit"></i> <?=translate('edit_school')?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="edit">
				<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
					<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $data->id; ?>">
					<div class="form-group mt-md">
						<label class="col-md-3 control-label"><?=translate('branch_name')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="branch_name" value="<?=set_value('branch_name', $data->name)?>" />
							<span class="error"><?=form_error('branch_name') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('school_name')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="school_name" value="<?=set_value('school_name', $data->school_name)?>" />
							<span class="error"><?=form_error('school_name') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('email')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="email" value="<?=set_value('email', $data->email)?>" />
							<span class="error"><?=form_error('email') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('password')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="password" class="form-control" name="password" value="<?=set_value('password')?>" />
							<span class="error"><?=form_error('password') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('retype_password')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="password" class="form-control" name="retype_password" value="<?=set_value('retype_password')?>" />
							<span class="error"><?=form_error('retype_password') ?></span>
						</div>
					</div>
					<!-- Removed non-existent field
                        <!-- Removed non-existent field
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('joining_date')?> <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="joining_date" value="<?=set_value('joining_date', date('Y-m-d'))?>" data-plugin-datepicker
                                data-plugin-options='{ "todayHighlight" : true }' />
                            </div>
                            <span class="error"><?=form_error('joining_date')?></span>
                        </div>
                        -->
                        -->
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('mobile_no')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="mobileno" value="<?=set_value('mobileno', $data->mobileno)?>" />
							<span class="error"><?=form_error('mobileno') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?=translate('currency')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="currency" value="<?=set_value('currency', $data->currency)?>" readonly/>
							<span class="error"><?=form_error('currency') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('currency_symbol')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="currency_symbol" value="<?=set_value('currency_symbol', $data->symbol)?>" readonly/>
							<span class="error"><?=form_error('currency_symbol'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('role')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								// Debug roles data
								echo "<!-- Roles Data: " . print_r($roles, true) . " -->";
								
								$array = array("" => translate('select'));
								if (!empty($roles)) {
									foreach($roles as $role) {
										$array[$role['id']] = $role['name'];
									}
								}
								$current_role = $this->db->select('role')->where('username', $data->email)->get('login_credential')->row()->role;
								echo form_dropdown("role_id", $array, set_value('role_id', $current_role), "class='form-control' data-plugin-selectTwo data-width='100%'");
							?>
							<span class="error"><?=form_error('role_id'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('city')?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="city" value="<?=set_value('city', $data->city)?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('state')?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="state" value="<?=set_value('state', $data->state)?>">
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?=translate('address')?></label>
						<div class="col-md-6 mb-md">
							<textarea type="text" rows="3" class="form-control" name="address" ><?=set_value('address', $data->address)?></textarea>
						</div>
					</div>
					<!-- Removed non-existent field
                        <!-- Removed non-existent field
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('contact_person_name')?> <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="contact_person_name" value="<?=set_value('contact_person_name')?>" />
                                <span class="error"><?=form_error('contact_person_name') ?></span>
                            </div>
                        </div>
                        -->
                        -->
					<!-- Removed non-existent field
                        <!-- Removed non-existent field
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('contact_person_gender')?> <span class="required">*</span></label>
                            <div class="col-md-6">
                                <?php
                                    $array = array(
                                        "" => translate('select'),
                                        "male" => translate('male'),
                                        "female" => translate('female')
                                    );
                                    echo form_dropdown("contact_person_gender", $array, set_value('contact_person_gender'), "class='form-control' data-plugin-selectTwo data-width='100%'");
                                ?>
                                <span class="error"><?=form_error('contact_person_gender') ?></span>
                            </div>
                        </div>
                        -->
                        -->
					<footer class="panel-footer mt-lg">
						<div class="row">
							<div class="col-md-2 col-md-offset-3">
								<button type="submit" class="btn btn-default btn-block" name="submit" value="save">
									<i class="fas fa-plus-circle"></i> <?=translate('update')?>
								</button>
							</div>
						</div>	
					</footer>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</section>