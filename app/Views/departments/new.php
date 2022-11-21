<?php
/**
 * @var object[] $departments
 * @var array[] $validation
 * @var string $dept_tree
 */
?>
<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>Create Department<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('layouts/flash') ?>
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h3>Create Department</h3>
                    <form method="post" action="/dashboard/departments">
                        <div class="form-group mb-2">
                            <label for="dept-name">Name</label>
                            <input class="form-control <?= array_key_exists('name', $validation) ? 'is-invalid' : '' ?>" value="<?= set_value('name') ?>" type="text" id="dept-name" name="name" placeholder="Department Name">
                            <small class="<?= array_key_exists('name', $validation) ? 'text-danger' : 'text-secondary' ?>">
                                <?= $validation['name'] ?? 'The department name is unique' ?>
                            </small>
                        </div>
                        <div class="form-group mb-2">
                            <label for="parent">Parent Department</label>
                            <select class="form-select" id="parent" name="parent">
                                <option value="">No Parent</option>
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept->id ?>" <?= set_select('parent', $dept->id) ?>><?= $dept->name ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="<?= array_key_exists('parent', $validation) ? 'text-danger' : 'text-secondary' ?>">
                                <?= $validation['parent'] ?? 'Select a Parent' ?>
                            </small>
                        </div>
                        <div class="d-grid gap-2">
                        <button class="btn btn-success" type="submit">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h3>Department Tree</h3>
                    <?= $dept_tree ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="toast-container position-fixed p-3 top-0 end-0" style="z-index: 40000" id="toast-container"></div>
<?= $this->endSection() ?>
