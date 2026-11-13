<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? '3d') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
    <?= $this->renderSection('content') ?>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
