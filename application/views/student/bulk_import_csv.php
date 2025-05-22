<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <?php echo form_open_multipart(base_url('student/csv_import'), array('class' => 'form-horizontal form-bordered validate'));
            echo form_hidden('import_mode', 'bulk');
            ?>
            <header class="panel-heading">
                <h4 class="panel-title">
                    <i class="fas fa-file-archive"></i> Bulk Student Import (CSV with Class & Section)
                </h4>
            </header>
            <div class="panel-body">
                <?php if (isset($_SESSION['bulkimport_success']) && $_SESSION['bulkimport_success']): ?>
                    <div class="alert alert-success">
                        Successfully imported <?php echo $_SESSION['bulkimport_success']; ?> students.
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['bulkimport_errors']) && is_array($_SESSION['bulkimport_errors']) && count($_SESSION['bulkimport_errors'])): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($_SESSION['bulkimport_errors'] as $err): ?>
                            <div><?php echo $err; ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="form-group mt-md">
                    <div class="col-md-12 mb-md">
                        <a class="btn btn-default pull-right" href="<?php echo base_url('uploads/multi_student_sample.csv'); ?>">
                            <i class='fas fa-file-download'></i> Download Sample Import File
                        </a>
                    </div>
                    <div class="col-md-12">
                        <div class="alert alert-subl">
                            <strong>Instructions :</strong><br/>
                            1. Download the sample file.<br/>
                            2. Open the downloaded 'csv' file and carefully fill the details of the student.<br/>
                            3. The date you are trying to enter the "Birthday" column make sure the date format is Y-m-d (<?php echo date('Y-m-d'); ?>).<br/>
                            4. Do not import duplicate students.<br/>
                            5. The ClassName and SectionName must match exactly as in your system.<br/>
                        </div>
                    </div>
                </div>
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
                            <i class="fas fa-plus-circle"></i> Import
                        </button>
                    </div>
                </div>
            </footer>
            <?php echo form_close();?>
        </section>
    </div>
</div>