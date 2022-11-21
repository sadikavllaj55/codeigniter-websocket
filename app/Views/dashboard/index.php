<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('layouts/flash') ?>
    <h2>Hello <?= session('user')->name ?></h2>
<?= $this->endSection() ?>