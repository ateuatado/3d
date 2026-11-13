<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\Audit\AuditService;
use CodeIgniter\HTTP\RedirectResponse;
use Config\AuthGroups;
use CodeIgniter\Shield\Entities\User;

final class AdminUsersController extends BaseController
{
    public function index(): string
    {
        $users = auth()->getProvider()
            ->withIdentities()
            ->withGroups()
            ->findAll();

        return view('admin/users/index', [
            'title' => 'Administradores | 3d',
            'users' => $users,
        ]);
    }

    public function new(): string
    {
        $groups = (new AuthGroups())->groups;
        unset($groups['user'], $groups['beta']);

        return view('admin/users/form', [
            'title' => 'Novo administrador | 3d',
            'groups' => $groups,
            'errors' => session('errors') ?? [],
        ]);
    }

    public function create(): RedirectResponse
    {
        $rules = [
            'email' => 'required|valid_email|max_length[255]',
            'password' => 'required|min_length[12]',
            'group' => 'required|in_list[admin,catalog,support,finance]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $provider = auth()->getProvider();
        if ($provider->findByCredentials(['email' => $this->request->getPost('email')]) !== null) {
            return redirect()->back()->withInput()->with('errors', [
                'email' => 'Já existe uma conta com este e-mail.',
            ]);
        }

        $user = $provider->createNewUser([
            'username' => null,
            'email' => strtolower(trim((string) $this->request->getPost('email'))),
            'password' => (string) $this->request->getPost('password'),
        ]);
        if (! $user instanceof User || ! $provider->save($user)) {
            return redirect()->back()->withInput()->with('error', 'Não foi possível criar o administrador.');
        }

        $user = $provider->findById($provider->getInsertID());
        $user?->addGroup((string) $this->request->getPost('group'));

        (new AuditService())->record('admin.user.created', 'user', $user?->id, 'success', [
            'group' => (string) $this->request->getPost('group'),
        ], auth()->user()?->id);

        return redirect()->to(site_url('admin/administradores'))->with('message', 'Administrador criado com sucesso.');
    }
}
