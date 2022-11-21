<?= $this->extend('layouts/auth') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h3 class="text-center">Login</h3>
<?= $this->include('layouts/flash') ?>
<form method="post" action="<?= base_url()?>/login">
    <div class="form-group">
        <label for="email">Enter Email</label>
        <input type="text" id="email" name="user_email" value="<?= set_value('user_email') ?>" class="form-control">
        <small class="text-danger"><?php echo $validation['user_email'] ?? '&nbsp;' ?> </small>
    </div>
    <div class="form-group">
        <label for="password">Enter Password</label>
        <input type="password" id="password" name="user_password" class="form-control">
        <small class="text-danger"><?php echo $validation['user_password'] ?? '&nbsp;' ?></small>
    </div>
    <div class="d-grid gap-2">
        <input type="submit" name="login" value="Login" class="btn btn-success">
        <small>No account? Register <a href="<?= base_url()?>/register">here</a></small>
        <small><a href="<?= base_url()?>/password/forgot">Forgot Password</a>?</small>
    </div>
</form>
<?= $this->endSection() ?>
