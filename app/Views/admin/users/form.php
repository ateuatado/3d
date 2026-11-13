<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="admin-page admin-form-page">
    <a class="back-link" href="<?= site_url('admin/administradores') ?>">← Voltar aos administradores</a>
    <h1>Novo administrador</h1>
    <p>O administrador receberá acesso conforme o grupo selecionado.</p>
    <?php if ($errors !== []): ?><div class="notice notice--error"><strong>Revise os campos:</strong><ul><?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form method="post" action="<?= site_url('admin/administradores') ?>" class="admin-form">
        <?= csrf_field() ?>
        <label>E-mail <input name="email" type="email" required value="<?= esc(old('email')) ?>"></label>
        <label>Senha temporária <input name="password" type="password" minlength="12" required><small>Use pelo menos 12 caracteres e entregue a senha por canal seguro.</small></label>
        <label>Grupo
            <select name="group" required>
                <?php foreach ($groups as $key => $group): ?><option value="<?= esc($key) ?>" <?= old('group') === $key ? 'selected' : '' ?>><?= esc($group['title']) ?> — <?= esc($group['description']) ?></option><?php endforeach; ?>
            </select>
        </label>
        <button type="submit">Criar administrador</button>
    </form>
</main>
<?= $this->endSection() ?>
