<?php

declare(strict_types=1);

namespace App\Services\Storage;

use CodeIgniter\HTTP\Files\UploadedFile;
use Config\Files as FilesConfig;
use RuntimeException;

final class PrivateFileStorage
{
    public function __construct(private readonly FilesConfig $config = new FilesConfig())
    {
    }

    public function store(UploadedFile $file, string $category, array $allowedExtensions, int $maxBytes): array
    {
        if (! $file->isValid() || $file->hasMoved()) {
            throw new RuntimeException('O arquivo enviado não é válido.');
        }

        if ($file->getSize() > $maxBytes) {
            throw new RuntimeException('O arquivo excede o limite permitido.');
        }

        $extension = strtolower($file->getExtension());
        if (! in_array($extension, $allowedExtensions, true)) {
            throw new RuntimeException('O formato do arquivo não é permitido.');
        }

        $directory = rtrim($this->config->privateRoot, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR . trim($category, DIRECTORY_SEPARATOR);
        if (! is_dir($directory) && ! mkdir($directory, 0750, true) && ! is_dir($directory)) {
            throw new RuntimeException('Não foi possível preparar o armazenamento privado.');
        }

        $storageName = bin2hex(random_bytes(16)) . '.' . $extension;
        $file->move($directory, $storageName);
        $absolutePath = $directory . DIRECTORY_SEPARATOR . $storageName;

        return [
            'original_name' => $file->getClientName(),
            'storage_name' => $storageName,
            'storage_path' => $absolutePath,
            'mime_type' => (string) $file->getMimeType(),
            'extension' => $extension,
            'size_bytes' => (int) filesize($absolutePath),
            'sha256' => hash_file('sha256', $absolutePath),
        ];
    }

    public function assertPrivatePath(string $path): void
    {
        $root = realpath($this->config->privateRoot);
        $candidate = realpath($path);
        if ($root === false || $candidate === false || ! str_starts_with($candidate, $root . DIRECTORY_SEPARATOR)) {
            throw new RuntimeException('Caminho de arquivo privado inválido.');
        }
    }
}
