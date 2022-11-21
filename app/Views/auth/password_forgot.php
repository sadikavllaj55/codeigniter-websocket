<?php
/**
 * @var array $validation
 */
?>
<?= $this->extend('layouts/auth') ?>

<?= $this->section('title') ?>Password Forgot<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h3 class="text-center">Password Forgot</h3>
<?= $this->include('layouts/flash') ?>
<form method="post" action="<?= base_url()?>/password/forgot">
    <div class="form-group mb-2">
        <label for="email">Enter Email</label>
        <input type="text" id="email" name="email" class="form-control" value="<?= set_value('email')?>">
        <small class="<?= array_key_exists('email', $validation) ? 'text-danger' : 'text-secondary' ?>">
            <?= $validation['email'] ?? 'Email of registered account' ?>
        </small>
    </div>
    <div class="d-grid gap-2">
        <input type="submit" value="Send reset link" class="btn btn-success">
        <small>No account? Register <a href="<?= base_url()?>/register">here</a></small>
    </div>
</form>
<?= $this->endSection() ?>
