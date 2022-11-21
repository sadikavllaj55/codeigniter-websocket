<?= $this->extend('layouts/auth') ?>

<?= $this->section('title') ?>Register<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h3 class="text-center">Register</h3>
<?= $this->include('layouts/flash') ?>
<form method="post" action="<?= base_url() ?>/register">
    <div class="form-group">
        <label for="username">Full Name</label>
        <input type="text" id="username" name="user_name" class="form-control" value="<?php echo set_value('user_name')?>">
        <small class="text-danger"><?= $validation['user_name'] ?? '&nbsp;' ?></small>
    </div>
    <div class="form-group">
        <label for="email_address">Email Address</label>
        <input id="email_address" type="text" name="email_address" class="form-control" value="<?php echo set_value('email_address')?>">
        <small class="text-danger"><?= $validation['email_address'] ?? '&nbsp;' ?></small>
    </div>
    <div class="form-group">
        <label for="user_password">Password</label>
        <input type="password" id="user_password" name="user_password" class="form-control">
        <small class="text-danger"><?= $validation['user_password'] ?? '&nbsp;' ?></small>
    </div>
    <div class="form-group">
        <label for="passconf">Password Confirmation</label>
        <input type="password" id="passconf" name="passconf" class="form-control">
        <small class="text-danger"><?= $validation['passconf'] ?? '&nbsp;' ?></small>
    </div>
    <div class="d-grid gap-2">
        <input type="submit" name="register" value="Register" class="btn btn-success">
        <small>Already registered? Login <a href="<?= base_url()?>/login">here</a></small>
    </div>
</form>
<?= $this->endSection() ?>