<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

final class OrderItemModel extends Model
{
    protected $table = 'order_items';
    protected $returnType = 'array';
    protected $allowedFields = [
        'order_id', 'model_id', 'model_version', 'description', 'quantity',
        'unit_price', 'total',
    ];
    protected $useTimestamps = false;
}
