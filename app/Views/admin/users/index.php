<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="admin-page">
    <div class="admin-header">
        <div><p class="eyebrow">Administração</p><h1>Administradores</h1></div>
        <a class="button" href="<?= site_url('admin/administradores/novo') ?>">Novo administrador</a>
    </div>
    <?php if ($message = session('message')): ?><p class="notice notice--success"><?= esc($message) ?></p><?php endif; ?>
    <div class="admin-table-wrap"><table class="admin-table">
        <thead><tr><th>E-mail</th><th>Grupos</th><th>Status</th><th>Último acesso</th></tr></thead>
        <tbody><?php foreach ($users as $user): ?><tr>
            <td><?= esc($user->email ?? 'sem e-mail') ?></td>
            <td><?= esc(implode(', ', $user->getGroups())) ?></td>
            <td><?= $user->active ? 'Ativo' : 'Inativo' ?></td>
            <td><?= esc((string) ($user->last_active ?? 'Nunca')) ?></td>
        </tr><?php endforeach; ?></tbody>
    </table></div>
</main>
<?= $this->endSection() ?>
