<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

final class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $returnType = 'array';
    protected $allowedFields = [
        'order_id', 'external_id', 'external_status', 'method', 'amount',
        'response_summary', 'confirmed_at', 'refunded_at',
    ];
    protected $useTimestamps = true;
}
