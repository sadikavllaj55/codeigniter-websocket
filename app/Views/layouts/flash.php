<?php

$typeClasses = [
    'info' => 'info',
    'debug' => 'secondary',
    'primary' => 'info',
    'success' => 'success',
    'error' => 'danger',
    'warning' => 'warning',
    'light' => 'light',
    'dark' => 'dark',
];

foreach ($typeClasses as $type => $class):
    if (session()->has($type)):?>
    <div class="alert alert-<?= $class ?>">
        <?= session()->getFlashdata($type) ?>
    </div>
    <?php endif;
endforeach; ?>
