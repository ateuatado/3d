<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="admin-page">
    <div class="admin-header">
        <div><p class="eyebrow">Administração</p><h1>Catálogo de modelos</h1></div>
        <?php if (auth()->user()?->can('catalog.manage')): ?><a class="button" href="<?= site_url('admin/catalogo/novo') ?>">Novo modelo</a><?php endif; ?>
    </div>
    <?php if ($message = session('message')): ?><p class="notice notice--success"><?= esc($message) ?></p><?php endif; ?>
    <?php if ($models === []): ?>
        <section class="empty-state"><h2>O catálogo ainda está vazio</h2><p>Cadastre o primeiro modelo para começar a publicar produtos.</p></section>
    <?php else: ?>
        <div class="admin-table-wrap"><table class="admin-table">
            <thead><tr><th>Modelo</th><th>Status</th><th>Preço</th><th>Atualizado</th></tr></thead>
            <tbody><?php foreach ($models as $model): ?><tr>
                <td><strong><?= esc($model['title']) ?></strong><br><small><?= esc($model['slug']) ?></small></td>
                <td><span class="status status--<?= esc($model['status']) ?>"><?= esc($model['status']) ?></span></td>
                <td><?= esc($model['currency']) ?> <?= number_format((float) $model['price'], 2, ',', '.') ?></td>
                <td><?= esc((string) ($model['updated_at'] ?? '')) ?></td>
            </tr><?php endforeach; ?></tbody>
        </table></div>
    <?php endif; ?>
</main>
<?= $this->endSection() ?>
