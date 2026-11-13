<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="home-page">
    <section class="hero">
        <p class="eyebrow">3d · modelos para imprimir</p>
        <h1>Encontre, visualize e aproveite modelos 3D.</h1>
        <p>Explore peças digitais, baixe modelos gratuitos e encontre inspiração para seus próximos projetos.</p>
        <a class="button" href="<?= site_url('catalogo') ?>">Explorar catálogo</a>
    </section>
    <section class="home-section">
        <div class="section-heading"><div><p class="eyebrow">Comece por aqui</p><h2>Modelos em destaque</h2></div><a href="<?= site_url('catalogo') ?>">Ver todos</a></div>
        <div class="model-grid">
            <?php foreach ($featuredModels as $model): ?>
                <article class="model-card"><div class="model-card__preview"><span>Pré-visualização 3D</span></div><div class="model-card__body"><p class="eyebrow"><?= esc($model['category'] ?? 'Modelo 3D') ?></p><h3><?= esc($model['title']) ?></h3><p><?= esc(mb_strimwidth(strip_tags((string) $model['description']), 0, 120, '...', 'UTF-8')) ?></p><div class="model-card__footer"><strong class="<?= (int) ($model['is_free'] ?? 0) === 1 ? 'price-free' : '' ?>"><?= (int) ($model['is_free'] ?? 0) === 1 ? 'Gratuito' : esc($model['currency']) . ' ' . number_format((float) $model['price'], 2, ',', '.') ?></strong><a href="<?= site_url('catalogo/' . rawurlencode($model['slug'])) ?>">Ver modelo</a></div></div></article>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="home-section home-section--free"><div class="section-heading"><div><p class="eyebrow">Para você experimentar</p><h2>Modelos gratuitos</h2></div></div><div class="model-grid"><?php foreach ($freeModels as $model): ?><article class="model-card"><div class="model-card__body"><p class="eyebrow">Download gratuito</p><h3><?= esc($model['title']) ?></h3><p><?= esc(mb_strimwidth(strip_tags((string) $model['description']), 0, 120, '...', 'UTF-8')) ?></p><a class="button" href="<?= site_url('catalogo/' . rawurlencode($model['slug'])) ?>">Ver e baixar</a></div></article><?php endforeach; ?><?php if ($freeModels === []): ?><p>Em breve teremos modelos gratuitos disponíveis.</p><?php endif; ?></div></section>
    <section class="custom-request"><p class="eyebrow">Precisa de algo específico?</p><h2>Solicite uma peça personalizada ou peça o modelo impresso.</h2><p>Conte o que você precisa e nossa equipe entra em contato pelo WhatsApp.</p><span class="button">Atendimento personalizado em breve</span></section>
</main>
<?= $this->endSection() ?>
