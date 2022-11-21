<?php
/**
 * @var object $department
 * @var object[] $employees
 * @var object[] $departments
 */
?>
<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?><?= $department->name ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('layouts/flash') ?>
<h2><?= $department->name ?></h2>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-md-4">
            <div class="card">
                <div class="card-body">
                    <form method="post">
                        <div class="form-group mb-2">
                            <label for="dept-name">Name</label>
                            <input class="form-control" type="text" id="dept-name" name="name" value="<?= $department->name ?>" placeholder="Department Name">
                            <span class="text-secondary">The department name is unique</span>
                        </div>
                        <div class="form-group mb-2">
                            <label for="parent">Name</label>
                            <select class="form-select" id="parent" name="parent">
                                <option>No Parent</option>
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept->id ?>" <?php if ($dept->id === $department->parent): ?> selected <?php endif; ?>><?= $dept->name ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="text-secondary">Select a Parent</span>
                        </div>
                        <div class="d-grid gap-2">
                        <button class="btn btn-success" type="submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-8">
            <table id="employee-table" class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($employees) == 0): ?>
                    <tr class="bg-warning">
                        <td class="text-center" colspan="3"><em>No employees on this department</em></td>
                    </tr>
                    <?php endif; ?>
                    <?php foreach ($employees as $employee): ?>
                    <tr data-id="<?= $employee->id ?>">
                        <td><a href="/dashboard/users/<?= $employee->id ?>"><?= $employee->name ?></a></td>
                        <td><?= $employee->email ?></td>
                        <td>
                            <button class="btn btn-sm btn-danger remove-employee-btn" data-id="<?= $employee->id ?>">Remove</button>
                            <button class="btn btn-sm btn-primary move-employee-btn" data-id="<?= $employee->id ?>">Transfer</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="remove-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Remove Employee</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="remove-submit" class="btn btn-danger">Remove</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="transfer-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Transfer Employee</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-6 d-flex" style="justify-content: right; align-items: center">
                        <div>From: <strong><?= $department->name ?></strong> To:</div>
                    </div>
                    <div class="col-6">
                        <select name="to" class="form-select">
                            <option>No Parent</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept->id ?>"><?= $dept->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="employee"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="transfer-submit" class="btn btn-primary">Transfer</button>
            </div>
        </div>
    </div>
</div>

<div class="toast-container position-fixed p-3 top-0 end-0" style="z-index: 40000" id="toast-container"></div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    var $remove_modal = $('#remove-modal');
    var $transfer_modal = $('#transfer-modal');

    $('.remove-employee-btn').on('click', function () {
        var user_id = $(this).data('id');
        $.ajax({
            url: '/dashboard/users/' + user_id,
            data: {json: 1},
            method: 'GET',
            success: function (data) {
                $remove_modal.find('.modal-body').html('<p>Are you sure you want to remove this employee?</p>' +
                    '<div class="user d-flex" style="flex-direction: column; align-items: center" class="text-center">' +
                    '<img style="width:90px; height: 90px; border-radius: 50%" class="img-fluid" src="/' + data.user.profile_image + '">' +
                    '<p class="lead" style="text-transform: uppercase;">' + data.user.name + '</p></div><input value="' + user_id + '" type="hidden">'
                );
                $remove_modal.modal('show');
            }
        });
    });

    $('.move-employee-btn').on('click', function () {
        var user_id = $(this).data('id');
        $.ajax({
            url: '/dashboard/users/' + user_id,
            data: {json: 1},
            method: 'GET',
            success: function (data) {
                $transfer_modal.find('.modal-body .employee').html('<p>Are you sure you want to transfer this employee?</p>' +
                    '<div class="user d-flex" style="flex-direction: column; align-items: center" class="text-center">' +
                    '<img style="width:90px; height: 90px; border-radius: 50%" class="img-fluid" src="/' + data.user.profile_image + '">' +
                    '<p class="lead" style="text-transform: uppercase;">' + data.user.name + '</p></div><input value="' + user_id + '" type="hidden">'
                );
                $transfer_modal.modal('show');
            }
        });
    });

    $('#remove-submit').on('click', function () {
        var user_id = $remove_modal.find('.modal-body input').val();

        $.ajax({
            url: '/dashboard/departments/<?= $department->id ?>/employees/' + user_id,
            method: 'DELETE',
            success: function (data) {
                if (data.status) {
                    removeRow(user_id);
                    showNotification('success', data.message);
                } else {
                    showNotification('danger', data.message);
                }

                $remove_modal.modal('hide');
            },
            error: function (error) {
                showNotification('danger', 'Server error. Could not remove this employee');
                $remove_modal.modal('hide');
            }
        });
    });

    $('#transfer-submit').on('click', function () {
        var user_id = $transfer_modal.find('.modal-body input').val();
        var new_dept = $transfer_modal.find('.modal-body select[name="to"]').val();

        $.ajax({
            url: '/dashboard/departments/<?= $department->id ?>/employees/' + user_id,
            method: 'POST',
            data: {to: new_dept},
            success: function (data) {
                if (data.status) {
                    removeRow(user_id);
                    showNotification('success', data.message);
                } else {
                    showNotification('danger', data.message);
                }

                $transfer_modal.modal('hide');
            },
            error: function (error) {
                showNotification('danger', 'Server error. Could not transfer this employee');
                $transfer_modal.modal('hide');
            }
        });
    });

    function removeRow(id) {
        var $tr = $('#employee-table > tbody > tr[data-id="' + id +'"]');
        $tr.remove();

        if ($('#employee-table > tbody > tr').length == 0) {
            $('#employee-table > tbody').append('<tr class="bg-warning"><td class="text-center" colspan="3"><em>No employees on this department</em></td></tr>')
        }
    }
</script>
<?= $this->endSection() ?>
