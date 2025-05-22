<div class="row">
	<div class="col-md-12">
		<section class="panel">
		<?php echo form_open_multipart($this->uri->uri_string(), array( 'class' => 'form-horizontal form-bordered validate'));?>	
			<header class="panel-heading">
				<h4 class="panel-title">
					<i class="fas fa-file-archive"></i> <?=translate('bulk_student_import')?>
				</h4>
			</header>
			<div class="panel-body">
			<?php if ($this->session->flashdata('csvimport')): ?>
				<div class="alert-danger p-sm"><?php echo $this->session->flashdata('csvimport'); ?></div>
			<?php endif; ?>
				<div class="form-group mt-md">
					<div class="col-md-12 mb-md">
						<a class="btn btn-default pull-right" href="<?=base_url('bulkstudentimport/csv_Sampledownloader')?>">
							<i class='fas fa-file-download'></i> Download Sample Import File
						</a>
					</div>
					<div class="col-md-12">
						<div class="alert alert-subl">
							<strong>Instructions :</strong><br/>
							1. Download the first sample file.<br/>
							2. Open the downloaded 'csv' file and carefully fill the details of the student. <br/>
							3. The date you are trying to enter the "Birthday" and "AdmissionDate" column make sure the date format is Y-m-d (<?=date('Y-m-d')?>). <br/>
							4. Do not import the duplicate "roll number" (unique in class) And Register No generated automatically. <br/>
							5. The Category name comes from another table, so for the "Category", enter Category ID (can be found on the Category page). <br/>
							6. If the guardian is present, enter the "Guardian Email" address, otherwise leave the "GuardianEmail" column blank.<br/>
							7. You must specify the "ClassID" and "SectionID" for each student in the CSV file.<br/>
							8. This bulk import allows importing students across multiple classes and sections in a single CSV file.
						</div>
					</div>
				</div>
			<?php if (is_superadmin_loggedin()): ?>
				<div class="form-group">
					<label class="control-label col-md-3"><?php echo translate('branch');?> <span class="required">*</span></label>
					<div class="col-md-6">
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"><?=form_error('branch_id')?></span>
					</div>
				</div>
			<?php endif; ?>
				<div class="form-group">
					<label class="control-label col-md-3">Select CSV File <span class="required">*</span></label>
					<div class="col-md-6 mb-lg">
						<input type="file" name="userfile" class="dropify" data-height="140" data-allowed-file-extensions="csv" />
						<?php echo form_error('userfile', '<label class="error">', '</label>'); ?>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-offset-3 col-md-2">
						<button type="submit" name="save" value="1" class="btn btn btn-default btn-block">
							<i class="fas fa-plus-circle"></i> <?=translate('import')?>
						</button>
					</div>
				</div>
			</footer>
			<?php echo form_close();?>
		</section>
	</div>
</div>