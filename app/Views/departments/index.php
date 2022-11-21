<?php
/**
 * @var object[] $departments
 * @var string[] $links
 * @var object[] $dept_tree
 * @var string $dept_html
 * @var string $term
 * @var int $page
 * @var int $num_pages
 */
?>
<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>Department List<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('layouts/flash') ?>
    <div class="align-items-center d-flex">
        <form method="get" class="position-relative ms-auto" action="/dashboard/departments">
            <input placeholder="Search Departments" type="search" name="term" class="form-control" value="<?= $term ?>">
        </form>
    </div>
    <h2>Department Tree</h2>
    <?= $dept_html ?>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Department</th>
            <th>Employees</th>
            <th>Parent</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($departments as $row): ?>
            <tr data-id="<?= $row->id ?>">
                <td><a href="/dashboard/departments/<?= $row->id ?>"><?= $row->name ?></a></td>
                <td><a data-id="<?= $row->id ?>" class="employee-link" style="cursor:pointer;"><?= $row->employees ?></a></td>
                <td>
                    <?php if ($row->parent): ?>
                    <a href="/dashboard/departments/<?= $row->parent ?>"><?= $row->parent_name ?></a>
                    <?php else: ?>
                    <em>No Parent</em>
                    <?php endif; ?>
                </td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm dept-edit" data-id="<?= $row->id ?>">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm dept-delete" data-id="<?= $row->id ?>">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Department</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-dept" method="post">
                        <div class="form-group mb-2">
                            <label for="dept-name">Name</label>
                            <input id="dept-name" name="name" class="form-control" placeholder="Department Name">
                        </div>
                        <div class="form-group">
                            <label for="dept-parent">Parent</label>
                            <select id="dept-parent" name="parent" class="form-select">
                                <option value="">No Parent</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" form="edit-dept" id="edit-submit" class="btn btn-success">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Department</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Are you sure you want to delete this department?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="delete-submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="users-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Department Employees</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed p-3 top-0 end-0" style="z-index: 40000" id="toast-container"></div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    var $edit_modal = $('#edit-modal');
    var $delete_modal = $('#delete-modal');
    var $users_modal = $('#users-modal');

    $('.dept-delete').on('click', function () {
        var id = $(this).data('id');
        $delete_modal.find('.modal-body').html('<p>Are you sure you want to delete this department?</p><input type="hidden" value="' + id + '">');
        $('#delete-submit').attr('data-id', id);
        $delete_modal.modal('show');
    });

    $('.dept-edit').on('click', function () {
        var id = $(this).data('id');

        $.ajax({
            url: '/dashboard/departments/' + id,
            method: 'GET',
            success: function (data) {
                $edit_modal.find('.modal-body form').attr('action', '/dashboard/departments/' + id);
                $edit_modal.find('.modal-body input[name="name"]').val(data.department.name);
                var $parent = $edit_modal.find('.modal-body select[name="parent"]');
                for (var i = 0; i < data.departments.length; i++) {
                    var $option = $('<option value="' + data.departments[i].id + '">' + data.departments[i].name + '</option>');
                    if (data.departments[i].id === data.department.parent) {
                        $option.prop('selected', true);
                    }
                    $parent.append($option);
                }
                $edit_modal.find('.modal-body input[name="name"]').val(data.department.name);
                $edit_modal.modal('show');
            },
            error: function (error) {
                showNotification('danger', error.message);
            }
        });

    });

    $('.employee-link').on('click', function () {
        var id = $(this).data('id');

        $.ajax({
            url: '/dashboard/departments/' + id,
            method: 'GET',
            success: function (data) {
                var $tbody = $users_modal.find('table tbody');
                $tbody.html('');
                for (var i = 0; i < data.employees.length; i++) {
                    var $row = $('<tr><td>' + data.employees[i].name + '</td><td>' + data.employees[i].email + '</td></tr>');
                    $tbody.append($row);
                }

                if (data.employees.length === 0) {
                    $tbody.append('<tr class="bg-warning"><td colspan="2" class="text-center"><em>No employees on this department</em></td></tr>')
                }
                $users_modal.modal('show');
            },
            error: function (error) {
                showNotification('danger', error.message);
            }
        });

    });

    $('#delete-submit').on('click', function () {
        var id = $(this).data('id');

        $.ajax({
            url: '<?= base_url('dashboard/departments') ?>/' + id,
            method: 'DELETE',
            success: function (data) {
                if (data.status) {
                    removeRow(id);
                    showNotification('success', 'Department was deleted');
                } else {
                    showNotification('danger', data.message);
                }

                $delete_modal.modal('hide');
            },
            error: function (error) {
                showNotification('danger', 'Server error. Could not remove the department');
                $delete_modal.modal('hide');
            }
        });
    });

    $('#edit-submit').on('click', function () {
        var $form = $('#edit-dept');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            success: function (data) {
                if (data.status) {
                    editRow(data.department);
                    showNotification('success', 'Department was updated');
                }

                $edit_modal.modal('hide');
            }
        });
    });

    function editRow(dept) {
        var $tr = $('table > tbody > tr[data-id="' + dept.id +'"]');
        $tr.find('td:nth-child(1) a').text(dept.name); // Dept name
        //$tr.find('td:nth-child(4)').html(user.parent);
    }

    function removeRow(id) {
        var $tr = $('table > tbody > tr[data-id="' + id +'"]');
        $tr.remove();
        // TODO: edit rows where parent was deleted
    }

</script>
<?= $this->endSection() ?>
