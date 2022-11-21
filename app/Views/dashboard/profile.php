<?php
/**
 * @var array $validation
 */
?>
<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?> Edit <?= $this->endsection() ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="<?= base_url() ?>/css/edit.css">
<?= $this->endsection() ?>


<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
            <div class="card h-100">
                <div class="card-body">
                    <div class="account-settings">
                        <div class="user-profile text-center">
                            <div class="user-avatar">
                                <img class="img-fluid" src="<?= base_url() . '/' .session('user')->profile_image ?>" alt="<?= session('user')->name ?>">
                            </div>
                            <h5 class="user-name"><?= session('user')->name ?></h5>
                            <h6 class="user-email"><?= session('user')->email ?></h6>
                        </div>
                        <div class="about">
                            <h5 class="text-center">About</h5>
                            <p><?= nl2br(session('user')->bio) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
            <form method="post" enctype="multipart/form-data">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h6 class="mb-2">Personal Details</h6>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="fullName">Full Name</label>
                                    <input type="text" name="name" class="form-control" id="fullName" placeholder="Enter full name" value="<?= session('user')->name ?>">
                                    <small class="text-secondary">Enter full name</small>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" value="<?= session('user')->email ?>">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="tel" class="form-control" id="phone" placeholder="Enter phone number" value="<?= session('user')->telephone ?>">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="website">Website URL</label>
                                    <input type="url" name="web" class="form-control" id="website" placeholder="Website url" value="<?= session('user')->website ?>">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="profile">Profile Picture</label>
                                    <input type="file" name="profile" class="form-control" id="profile" placeholder="Profile Picture">
                                    <small class="<?= array_key_exists('profile', $validation) ? 'text-danger' : 'text-secondary' ?>">
                                        <?= $validation['profile'] ?? 'Upload an image as your profile picture' ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="bio">About me</label>
                                    <textarea name="bio" class="form-control" id="bio" placeholder="My Bio"><?= session('user')->bio ?></textarea>
                                    <small class="text-secondary">A short bio about yourself</small>
                                </div>
                            </div>
                        </div>
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h6 class="mt-3 mb-2">Address</h6>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="street">Street</label>
                                    <input type="text" name="address" class="form-control" id="street" placeholder="Enter Street" value="<?= session('user')->address ?>">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" name="city" class="form-control" id="city" placeholder="Enter City" value="<?= session('user')->city ?>">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <input type="text" name="country" class="form-control" id="country" placeholder="Enter Country" value="<?= session('user')->country ?>">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="zip">Zip Code</label>
                                    <input type="text" name="zip" class="form-control" id="zip" placeholder="Zip Code" value="<?= session('user')->zipcode ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <?= $this->include('layouts/flash') ?>
                                <input type="submit" value="Update" class="btn btn-success">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endsection() ?>
