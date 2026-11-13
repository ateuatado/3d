<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

final class ModelModel extends Model
{
    protected $table = 'models';
    protected $returnType = 'array';
    protected $allowedFields = [
        'title', 'slug', 'description', 'category', 'tags', 'dimensions', 'unit',
        'intended_use', 'compatibility_notes', 'license_terms', 'price', 'currency',
        'status', 'published_version', 'created_by', 'updated_by',
    ];
    protected $useTimestamps = true;
}
