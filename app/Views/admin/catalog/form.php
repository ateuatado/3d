<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="admin-page admin-form-page">
    <a class="back-link" href="<?= site_url('admin/catalogo') ?>">← Voltar ao catálogo administrativo</a>
    <h1>Novo modelo 3D</h1>
    <?php if ($errors !== []): ?><div class="notice notice--error"><strong>Revise os campos:</strong><ul><?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form method="post" action="<?= site_url('admin/catalogo') ?>" enctype="multipart/form-data" class="admin-form">
        <?= csrf_field() ?>
        <label>Título <input name="title" required value="<?= esc(old('title')) ?>"></label>
        <label>Descrição <textarea name="description" required rows="6"><?= esc(old('description')) ?></textarea></label>
        <div class="form-grid">
            <label>Categoria <input name="category" value="<?= esc(old('category')) ?>"></label>
            <label>Etiquetas <input name="tags" placeholder="ex.: arquitetura, decoração" value="<?= esc(old('tags')) ?>"></label>
            <label>Preço (BRL) <input name="price" type="number" min="0" step="0.01" required value="<?= esc(old('price')) ?>"></label>
            <label>Dimensões <input name="dimensions" value="<?= esc(old('dimensions')) ?>"></label>
            <label>Unidade <input name="unit" placeholder="mm, cm, m" value="<?= esc(old('unit')) ?>"></label>
            <label>Uso indicado <input name="intended_use" value="<?= esc(old('intended_use')) ?>"></label>
        </div>
        <label>Compatibilidade <textarea name="compatibility_notes" rows="3"><?= esc(old('compatibility_notes')) ?></textarea></label>
        <label>Termos de licença <textarea name="license_terms" required rows="4"><?= esc(old('license_terms')) ?></textarea></label>
        <div class="form-grid">
            <label>Arquivo original <input name="original_file" type="file" required accept=".stl,.obj,.fbx,.glb,.gltf,.3ds,.ply,.step,.stp,.iges,.igs,.zip"></label>
            <label>Pré-visualização GLB/GLTF <input name="preview_file" type="file" accept=".glb,.gltf"></label>
        </div>
        <?php if (auth()->user()?->can('catalog.publish')): ?><label class="checkbox"><input name="publish" value="1" type="checkbox"> Publicar imediatamente</label><?php endif; ?>
        <button type="submit">Salvar modelo</button>
    </form>
</main>
<?= $this->endSection() ?>
