<?php
/**
 * @var object[] $users
 * @var string[] $links
 * @var string $term
 * @var int $page
 * @var int $num_pages
 */
?>
<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>Employee List<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('layouts/flash') ?>
    <div class="align-items-center d-flex">
        <form method="get" class="position-relative ms-auto" action="/dashboard/users">
            <input placeholder="Search Users" type="search" name="term" class="form-control" value="<?= $term ?>">
        </form>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Img</th>
            <th>Name</th>
            <th>Email</th>
            <th>Department</th>
            <th>Address</th>
            <th>Added</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr data-id="<?= $user->id ?>">
                <td><img class="img-fluid" style="width: 50px" src="<?= base_url() . '/' . $user->profile_image ?>" alt="<?= $user->name ?>"></td>
                <td><?= $user->name ?></td>
                <td><?= $user->email ?></td>
                <td><?= $user->department ?></td>
                <td><small><?=
                    implode('<br>', [$user->address, $user->zipcode, $user->city, $user->country])
                ?></small></td>
                <td><?= $user->created ?></td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm user-edit" data-id="<?= $user->id ?>">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm user-delete" data-id="<?= $user->id ?>">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <nav aria-label="User Pagination">
        <ul class="pagination justify-content-center">
            <li class="page-item<?php if ($page == 1):?> disabled<?php endif; ?>">
                <a class="page-link" href="<?= $links[max(1, $page - 1)] ?>">Previous</a>
            </li>
            <?php for($i = 1; $i <= $num_pages; $i++): ?>
            <li class="page-item<?php if ($page == $i):?> active<?php endif; ?>" <?php if ($page == $i):?>aria-current="page"<?php endif; ?>>
                <a class="page-link" href="<?= $links[$i] ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item<?php if ($page == $num_pages):?> disabled<?php endif; ?>">
                <a class="page-link" href="<?= $links[min($num_pages, $page + 1)] ?>">Next</a>
            </li>
        </ul>
    </nav>
    <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit User</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="edit-submit" class="btn btn-success">Update</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete User</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="delete-submit" class="btn btn-danger">Delete</button>
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

    $(document).on('click', '.user-edit', function () {
        var id = $(this).data('id'); // data-id
        $.ajax({
            url: '<?= base_url('dashboard/users') ?>/' + id,
            method: 'GET',
            success: function (data) {
                $edit_modal.find('.modal-body').html(data);
                $edit_modal.modal('show');
            }
        });
    });

    $(document).on('click', '.user-delete', function () {
        var id = $(this).data('id');
        $delete_modal.find('.modal-body').html('<p>Are you sure you want to delete this user?</p><input type="hidden" value="' + id + '">');
        $('#delete-submit').attr('data-id', id);
        $delete_modal.modal('show');
    });

    $('#edit-submit').on('click', function () {
        var $form = $('#edit-form');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            success: function (data) {
                if (data.status) {
                    editRow(data.user);
                    showNotification('success', 'User was updated');
                }

                $edit_modal.modal('hide');
            }
        });
    });

    $('#delete-submit').on('click', function () {
        var id = $(this).data('id');

        $.ajax({
            url: '<?= base_url('dashboard/users') ?>/' + id,
            method: 'DELETE',
            success: function (data) {
                if (data.status) {
                    removeRow(id);
                    showNotification('success', 'User was deleted');
                } else {
                    showNotification('danger', data.message);
                }

                $delete_modal.modal('hide');
            }
        });
    });

    function editRow(user) {
        var $tr = $('table > tbody > tr[data-id="' + user.id +'"]');
        $tr.find('td:nth-child(2)').html(user.name);
        $tr.find('td:nth-child(3)').html(user.email);
        $tr.find('td:nth-child(4)').html(user.department);
        $tr.find('td:nth-child(5)').html(user.address);
    }

    function removeRow(id) {
        var $tr = $('table > tbody > tr[data-id="' + id +'"]');
        $tr.remove();
    }
</script>
<?= $this->endSection() ?>
