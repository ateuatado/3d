<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

final class CustomRequestModel extends Model
{
    protected $table = 'custom_requests';
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id', 'name', 'email', 'phone', 'description', 'requirements',
        'desired_format', 'status', 'internal_notes', 'assigned_to',
    ];
    protected $useTimestamps = true;
}
