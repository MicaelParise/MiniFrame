<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'MiniFrame' ?></title>
</head>
<body>
    <?php App\Core\View::component('header'); ?>

    <main>
        <?php App\Core\View::yield('content'); ?>
    </main>

    <?php App\Core\View::component('footer'); ?>
</body>
</html>