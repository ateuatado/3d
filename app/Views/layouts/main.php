<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? '3d') ?></title>
    <?php $cssPath = FCPATH . 'assets/css/app.css'; ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') . '?v=' . (is_file($cssPath) ? filemtime($cssPath) : time()) ?>">
</head>
<body>
    <header class="site-header">
        <nav class="site-nav" aria-label="Navegação principal">
            <a class="site-brand" href="<?= site_url('/') ?>">3d</a>
            <div class="site-nav__links">
                <a href="<?= site_url('/') ?>">Início</a>
                <a href="<?= site_url('catalogo') ?>">Catálogo</a>
                <a href="<?= site_url('catalogo?gratuitos=1') ?>">Modelos gratuitos</a>
                <?php if (auth()->loggedIn()): ?>
                    <a href="<?= site_url('admin/catalogo') ?>">Área administrativa</a>
                    <form method="post" action="<?= site_url('logout') ?>" class="site-nav__logout">
                        <?= csrf_field() ?><button type="submit">Sair</button>
                    </form>
                <?php else: ?>
                    <a class="site-nav__login" href="<?= site_url('login') ?>">Entrar</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <?= $this->renderSection('content') ?>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
