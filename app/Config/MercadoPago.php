<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Config\BaseConfig;

final class MercadoPago extends BaseConfig
{
    public string $accessToken = '';
    public string $webhookSecret = '';
    public string $webhookPath = 'integracoes/mercado-pago/webhook';
    public string $currency = 'BRL';
}
