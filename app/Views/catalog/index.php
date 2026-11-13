<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="catalog-page">
    <header class="catalog-header">
        <p class="eyebrow">3d · modelos digitais</p>
        <h1>Catálogo de modelos 3D</h1>
        <p>Encontre, visualize e compre modelos para seus projetos.</p>
        <form method="get" action="<?= site_url('catalogo') ?>" class="search-form">
            <label for="catalog-search">Pesquisar modelos</label>
            <div>
                <input id="catalog-search" name="q" value="<?= esc($term) ?>" placeholder="Ex.: personagem, arquitetura, peça">
                <button type="submit">Pesquisar</button>
            </div>
        </form>
    </header>

    <?php if ($models === []): ?>
        <section class="empty-state">
            <h2>Nenhum modelo encontrado</h2>
            <p><?= $term !== '' ? 'Tente outra busca.' : 'O catálogo será exibido aqui assim que os primeiros modelos forem publicados.' ?></p>
        </section>
    <?php else: ?>
        <section class="model-grid" aria-label="Modelos publicados">
            <?php foreach ($models as $model): ?>
                <article class="model-card">
                    <div class="model-card__preview">
                        <span>Pré-visualização 3D</span>
                    </div>
                    <div class="model-card__body">
                        <p class="eyebrow"><?= esc($model['category'] ?? 'Modelo 3D') ?></p>
                        <h2><?= esc($model['title']) ?></h2>
                        <p><?= esc(character_limiter(strip_tags($model['description']), 140)) ?></p>
                        <div class="model-card__footer">
                            <strong><?= esc($model['currency']) ?> <?= number_format((float) $model['price'], 2, ',', '.') ?></strong>
                            <a href="<?= site_url('catalogo/' . rawurlencode($model['slug'])) ?>">Ver modelo</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"></script>
<script type="module" src="<?= base_url('assets/js/catalog-viewer.js') ?>"></script>
<?= $this->endSection() ?>
