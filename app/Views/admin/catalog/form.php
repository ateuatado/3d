<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="admin-page admin-form-page">
    <a class="back-link" href="<?= site_url('admin/catalogo') ?>">← Voltar ao catálogo administrativo</a>
    <h1><?= $model === null ? 'Novo modelo 3D' : 'Editar modelo 3D' ?></h1>
    <?php if ($errors !== []): ?><div class="notice notice--error"><strong>Revise os campos:</strong><ul><?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <?php $value = static fn (string $field): string => esc(old($field) ?: (string) ($model[$field] ?? '')); ?>
    <form method="post" action="<?= $model === null ? site_url('admin/catalogo') : site_url('admin/catalogo/' . $model['id']) ?>" enctype="multipart/form-data" class="admin-form">
        <?= csrf_field() ?>
        <label>Título <input name="title" required value="<?= $value('title') ?>"></label>
        <label>Descrição <textarea name="description" required rows="6"><?= $value('description') ?></textarea></label>
        <div class="form-grid">
            <label>Categoria <input name="category" value="<?= $value('category') ?>"></label>
            <label>Etiquetas <input name="tags" placeholder="ex.: arquitetura, decoração" value="<?= $value('tags') ?>"></label>
            <label>Preço (BRL) <input name="price" type="number" min="0" step="0.01" required value="<?= $value('price') ?>"></label>
            <label>Dimensões <input name="dimensions" value="<?= $value('dimensions') ?>"></label>
            <label>Unidade <input name="unit" placeholder="mm, cm, m" value="<?= $value('unit') ?>"></label>
            <label>Uso indicado <input name="intended_use" value="<?= $value('intended_use') ?>"></label>
        </div>
        <label>Compatibilidade <textarea name="compatibility_notes" rows="3"><?= $value('compatibility_notes') ?></textarea></label>
        <label>Termos de licença <textarea name="license_terms" required rows="4"><?= $value('license_terms') ?></textarea></label>
        <div class="form-grid">
            <label>Arquivo original <input name="original_file" type="file" <?= $model === null ? 'required' : '' ?> accept=".stl,.obj,.fbx,.glb,.gltf,.3ds,.ply,.step,.stp,.iges,.igs,.zip"></label>
            <label>Pré-visualização GLB/GLTF <input name="preview_file" type="file" accept=".glb,.gltf"></label>
        </div>
        <?php if (auth()->user()?->can('catalog.publish')): ?><label class="checkbox"><input name="publish" value="1" type="checkbox" <?= ($model !== null && $model['status'] === 'published') ? 'checked' : '' ?>> Publicar modelo</label><?php endif; ?>
        <button type="submit"><?= $model === null ? 'Salvar modelo' : 'Atualizar modelo' ?></button>
    </form>
</main>
<?= $this->endSection() ?>
