<?php
// ...existing code...
?>
<ul class="nav nav-tabs">
    <li class="active">
        <a href="#csv_import" data-toggle="tab"><i class="fas fa-file-csv"></i> CSV Import</a>
    </li>
    <li>
        <a href="#bulk_import" data-toggle="tab"><i class="fas fa-users"></i> Bulk Import</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="csv_import">
        <?php include(APPPATH.'views/student/multi_add.php'); ?>
    </div>
    <div class="tab-pane" id="bulk_import">
        <?php include(APPPATH.'views/student/bulk_import_csv.php'); ?>
    </div>
</div>
<?php
// ...existing code...
?>