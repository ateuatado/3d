<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Config\BaseConfig;

final class Files extends BaseConfig
{
    public string $privateRoot = WRITEPATH . 'uploads/private';
    public int $maxModelFileSize = 524288000;
    public int $maxRequestFileSize = 10485760;
    public array $modelExtensions = [
        'stl', 'obj', 'fbx', 'glb', 'gltf', '3ds', 'ply', 'step', 'stp', 'iges', 'igs', 'zip',
    ];
    public array $previewExtensions = ['glb', 'gltf'];
    public array $requestExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'zip', 'stl', 'obj'];
}
