<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="catalog-page model-detail">
    <a class="back-link" href="<?= site_url('catalogo') ?>">← Voltar ao catálogo</a>
    <div class="model-detail__grid">
        <section>
            <div
                class="model-viewer"
                data-model-viewer
                data-source="<?= $preview !== null ? site_url('catalogo/preview/' . $preview['id']) : '' ?>"
                data-alt="<?= esc($model['title']) ?>"
            ></div>
        </section>
        <section class="model-detail__info">
            <p class="eyebrow"><?= esc($model['category'] ?? 'Modelo 3D') ?></p>
            <h1><?= esc($model['title']) ?></h1>
            <p><?= nl2br(esc($model['description'])) ?></p>
            <dl class="model-specs">
                <?php if (! empty($model['dimensions'])): ?><div><dt>Dimensões</dt><dd><?= esc($model['dimensions']) ?></dd></div><?php endif; ?>
                <?php if (! empty($model['unit'])): ?><div><dt>Unidade</dt><dd><?= esc($model['unit']) ?></dd></div><?php endif; ?>
                <?php if (! empty($model['intended_use'])): ?><div><dt>Uso indicado</dt><dd><?= esc($model['intended_use']) ?></dd></div><?php endif; ?>
            </dl>
            <div class="purchase-box">
                <strong><?= esc($model['currency']) ?> <?= number_format((float) $model['price'], 2, ',', '.') ?></strong>
                <a class="button" href="<?= site_url('login') ?>">Entrar para comprar</a>
                <small>O download é liberado somente após a confirmação do pagamento.</small>
            </div>
        </section>
    </div>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"></script>
<script type="module" src="<?= base_url('assets/js/catalog-viewer.js') ?>"></script>
<?= $this->endSection() ?>
