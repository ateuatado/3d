<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

final class ModelFileModel extends Model
{
    protected $table = 'model_files';
    protected $returnType = 'array';
    protected $allowedFields = [
        'model_id', 'version', 'kind', 'original_name', 'storage_name',
        'storage_path', 'mime_type', 'extension', 'size_bytes', 'sha256', 'status',
    ];
    protected $useTimestamps = false;
}
