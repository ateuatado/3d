<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

final class OrderModel extends Model
{
    protected $table = 'orders';
    protected $returnType = 'array';
    protected $allowedFields = [
        'public_number', 'user_id', 'status', 'subtotal', 'discount', 'total',
        'currency', 'paid_at', 'cancelled_at', 'cancellation_reason',
    ];
    protected $useTimestamps = true;
}
