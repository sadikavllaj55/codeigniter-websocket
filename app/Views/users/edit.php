<?php
/**
 * @var int $id
 * @var object $user
 */
?>
<form method="post" action="/dashboard/users/<?= $user->id ?>" id="edit-form">
    <div class="row mb-2">
        <div class="col">
            <div class="form-group">
                <label for="fullName">Full Name</label>
                <input type="text" name="name" class="form-control" id="fullName" placeholder="Enter full name" value="<?= $user->name ?>">
                <small class="text-secondary">Enter full name</small>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" value="<?= $user->email ?>">
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col">
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="tel" class="form-control" id="phone" placeholder="Enter phone number" value="<?= $user->telephone ?>">
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="website">Website URL</label>
                <input type="url" name="web" class="form-control" id="website" placeholder="Website url" value="<?= $user->website ?>">
            </div>
        </div>
    </div>
    <div class="form-group mb-2">
        <label for="profile">Profile Picture</label>
        <input type="file" name="profile" class="form-control" id="profile" placeholder="Profile Picture">
        <small class="text-secondary">Upload an image as your profile picture</small>
    </div>
    <div class="form-group mb-2">
        <div class="form-group">
            <label for="bio">About me</label>
            <textarea name="bio" class="form-control" id="bio" placeholder="My Bio"><?= $user->bio ?></textarea>
            <small class="text-secondary">A short bio about yourself</small>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col">
            <div class="form-group">
                <label for="street">Street</label>
                <input type="text" name="address" class="form-control" id="street" placeholder="Enter Street" value="<?= $user->address ?>">
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" name="city" class="form-control" id="city" placeholder="Enter City" value="<?= $user->city ?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" name="country" class="form-control" id="country" placeholder="Enter Country" value="<?= $user->country ?>">
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="zip">Zip Code</label>
                <input type="text" name="zip" class="form-control" id="zip" placeholder="Zip Code" value="<?= $user->zipcode ?>">
            </div>
        </div>
    </div>
</form>