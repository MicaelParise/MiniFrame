<?php App\Core\View::extends('main'); ?>

<?php App\Core\View::start('content'); ?>
    <h1>Bem-vindo ao MiniFrame</h1>
    <p>Versão <?= $version ?? '1.0.0' ?></p>
<?php App\Core\View::end(); ?>