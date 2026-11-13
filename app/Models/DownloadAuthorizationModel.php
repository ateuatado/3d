<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

final class DownloadAuthorizationModel extends Model
{
    protected $table = 'download_authorizations';
    protected $returnType = 'array';
    protected $allowedFields = [
        'token_hash', 'user_id', 'order_id', 'order_item_id', 'model_file_id',
        'expires_at', 'max_uses', 'used_count', 'revoked_at', 'last_download_at',
    ];
    protected $useTimestamps = false;
}
