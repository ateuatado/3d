<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

final class AuditEventModel extends Model
{
    protected $table = 'audit_events';
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id', 'action', 'entity', 'entity_id', 'result', 'metadata',
        'ip_address', 'correlation_id', 'created_at',
    ];
    protected $useTimestamps = false;
}
