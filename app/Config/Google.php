<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Config\BaseConfig;

final class Google extends BaseConfig
{
    public string $clientId = '';
    public string $clientSecret = '';
    public string $redirectUri = 'https://3d.test/login/google/callback';
    public array $scopes = ['openid', 'email', 'profile'];
}
