<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo translate('dashboard'); ?></a></li>
                        <li class="breadcrumb-item active"><?php echo translate('role_management'); ?></li>
                    </ol>
                </div>
                <h4 class="page-title"><?php echo translate('role_management'); ?></h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <h4 class="header-title"><?php echo translate('role_management'); ?></h4>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="role-management-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo translate('user'); ?></th>
                                    <th><?php echo translate('current_role'); ?></th>
                                    <th><?php echo translate('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $users = $this->userrole_model->getTeachersList();
                                if (!empty($users)) {
                                    foreach ($users as $key => $user) {
                                ?>
                                        <tr>
                                            <td><?php echo $key + 1; ?></td>
                                            <td><?php echo $user->name; ?></td>
                                            <td><?php echo $user->role; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" onclick="editRole(<?php echo $user->id; ?>)">
                                                    <i class="fas fa-edit"></i> <?php echo translate('edit'); ?>
                                                </button>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Role Edit Modal -->
<div id="roleEditModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo translate('edit_role'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="roleEditForm" method="post">
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="form-group">
                        <label><?php echo translate('select_role'); ?></label>
                        <select class="form-control" name="role_id" id="role_id">
                            <option value=""><?php echo translate('select'); ?></option>
                            <?php foreach ($roles as $role) { ?>
                                <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><?php echo translate('close'); ?></button>
                <button type="button" class="btn btn-primary waves-effect" onclick="updateRole()"><?php echo translate('save'); ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function editRole(user_id) {
        $.ajax({
            url: base_url + 'userrole/get_user_role',
            type: 'POST',
            data: {
                user_id: user_id
            },
            dataType: 'json',
            success: function(response) {
                $('#user_id').val(response.user_id);
                $('#role_id').val(response.role);
                $('#roleEditModal').modal('show');
            }
        });
    }

    function updateRole() {
        var formData = $('#roleEditForm').serialize();
        $.ajax({
            url: base_url + 'userrole/update_role',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    notification('success', response.message);
                    $('#roleEditModal').modal('hide');
                    location.reload();
                } else {
                    notification('error', response.message);
                }
            }
        });
    }
</script> 