<?php
/**
 * @var object $user
 */
?>
<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>Edit <?= $user->name ?><?= $this->endSection() ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="<?= base_url() ?>/css/edit.css">
<?= $this->endsection() ?>

<?= $this->section('content') ?>
<?= $this->include('layouts/flash') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-12">
            <div class="card h-100">
                <div class="card-body">
                    <div class="account-settings">
                        <div class="user-profile text-center">
                            <div class="user-avatar">
                                <img class="img-fluid" src="/<?= $user->profile_image ?>" alt="<?= $user->name ?>">
                            </div>
                            <h5 class="user-name"><?= $user->name ?></h5>
                            <h6 class="user-email"><?= $user->email ?></h6>
                        </div>
                        <div class="about">
                            <h5 class="text-center">About</h5>
                            <p><?= $user->bio ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-8 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?= $this->include('users/edit') ?>
                    <button type="submit" form="edit-form" class="mt-2 btn btn-success">Edit User</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
</script>
<?= $this->endSection() ?>
