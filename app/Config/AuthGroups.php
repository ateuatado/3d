<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = 'user';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of the group info.
     *
     * Whatever value you assign as the key will be used to refer to the group
     * when using functions such as:
     *      $user->addGroup('superadmin');
     *
     * @var array<string, array<string, string>>
     *
     * @see https://codeigniter4.github.io/shield/quick_start_guide/using_authorization/#change-available-groups for more info
     */
    public array $groups = [
        'superadmin' => [
            'title'       => 'Super Admin',
            'description' => 'Complete control of the site.',
        ],
        'admin' => [
            'title'       => 'Administrador',
            'description' => 'Administrador geral da plataforma.',
        ],
        'catalog' => [
            'title'       => 'Catálogo',
            'description' => 'Gerencia modelos, arquivos e publicação.',
        ],
        'support' => [
            'title'       => 'Atendimento',
            'description' => 'Gerencia solicitações e atendimento ao cliente.',
        ],
        'finance' => [
            'title'       => 'Financeiro',
            'description' => 'Consulta pedidos e pagamentos.',
        ],
        'developer' => [
            'title'       => 'Developer',
            'description' => 'Site programmers.',
        ],
        'user' => [
            'title'       => 'User',
            'description' => 'General users of the site. Often customers.',
        ],
        'beta' => [
            'title'       => 'Beta User',
            'description' => 'Has access to beta-level features.',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        'admin.access'        => 'Pode acessar a área administrativa',
        'admin.settings'      => 'Pode acessar as configurações principais',
        'users.manage-admins' => 'Pode gerenciar outros administradores',
        'catalog.manage'      => 'Pode gerenciar modelos e arquivos',
        'catalog.publish'     => 'Pode publicar ou retirar modelos',
        'orders.view'         => 'Pode consultar pedidos',
        'payments.view'       => 'Pode consultar pagamentos',
        'custom-requests.manage' => 'Pode gerenciar solicitações personalizadas',
        'audit.view'          => 'Pode consultar auditoria',
        'users.create'        => 'Can create new non-admin users',
        'users.edit'          => 'Can edit existing non-admin users',
        'users.delete'        => 'Can delete existing non-admin users',
        'beta.access'         => 'Can access beta-level features',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     *
     * This defines group-level permissions.
     */
    public array $matrix = [
        'superadmin' => [
            'admin.*',
            'users.*',
            'beta.*',
            'catalog.*',
            'orders.*',
            'payments.*',
            'custom-requests.*',
            'audit.*',
        ],
        'admin' => [
            'admin.access',
            'catalog.*',
            'orders.*',
            'payments.*',
            'custom-requests.*',
            'audit.view',
            'users.create',
            'users.edit',
            'users.delete',
            'beta.access',
        ],
        'developer' => [
            'admin.access',
            'admin.settings',
            'users.create',
            'users.edit',
            'beta.access',
        ],
        'catalog' => [
            'admin.access',
            'catalog.manage',
            'catalog.publish',
        ],
        'support' => [
            'admin.access',
            'custom-requests.manage',
            'orders.view',
        ],
        'finance' => [
            'admin.access',
            'orders.view',
            'payments.view',
        ],
        'user' => [],
        'beta' => [
            'beta.access',
        ],
    ];
}
