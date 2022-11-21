<?php
/**
 * @var string $token
 * @var string $email
 * @var array $validation
 */
?>
<?= $this->extend('layouts/auth') ?>

<?= $this->section('title') ?>Password Reset<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h3 class="text-center">Password Reset</h3>
<?= $this->include('layouts/flash') ?>
<form method="post">
    <div class="form-group mb-2">
        <label for="user_email">Email</label>
        <input type="text" id="user_email" value="<?= $email ?>" name="user_email" class="form-control" readonly>
        <small class="text-secondary">The user email</small>
    </div>
    <div class="form-group mb-2">
        <label for="password">New Password</label>
        <input type="password" id="password" name="password" class="form-control">
        <small class="<?= array_key_exists('password', $validation) ? 'text-danger' : 'text-secondary' ?>">
            <?php echo $validation['password'] ?? 'Password needs to be at least 8 characters.' ?>
        </small>
    </div>
    <div class="form-group mb-2">
        <label for="password_confirm">Password Confirm</label>
        <input type="password" id="password_confirm" name="password_confirm" class="form-control">
        <small class="<?= array_key_exists('password_confirm', $validation) ? 'text-danger' : 'text-secondary' ?>">
            <?php echo $validation['password_confirm'] ?? 'Should match the password' ?>
        </small>
    </div>
    <div class="d-grid gap-2">
        <input type="submit" value="Reset Password" class="btn btn-success">
        <small>No account? Register <a href="<?= base_url()?>/register">here</a></small>
    </div>
</form>
<?= $this->endSection() ?>
