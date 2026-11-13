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

        $extension = self::extensionOf($file);
        if (! in_array($extension, $allowedExtensions, true)) {
            throw new RuntimeException('O formato do arquivo não é permitido.');
        }
        if (in_array($extension, ['glb', 'gltf'], true) && ! self::isValidGltf($file)) {
            throw new RuntimeException('O arquivo GLB/GLTF não é válido.');
        }

        // Os metadados precisam ser lidos enquanto o temporário ainda existe.
        // Depois de move(), o PHP remove o arquivo em C:\xampp\tmp.
        $originalName = $file->getClientName();
        $mimeType = (string) $file->getMimeType();
        $sizeBytes = (int) $file->getSize();

        $directory = rtrim($this->config->privateRoot, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR . trim($category, DIRECTORY_SEPARATOR);
        if (! is_dir($directory) && ! mkdir($directory, 0750, true) && ! is_dir($directory)) {
            throw new RuntimeException('Não foi possível preparar o armazenamento privado.');
        }

        $storageName = bin2hex(random_bytes(16)) . '.' . $extension;
        $file->move($directory, $storageName);
        $absolutePath = $directory . DIRECTORY_SEPARATOR . $storageName;

        return [
            'original_name' => $originalName,
            'storage_name' => $storageName,
            'storage_path' => $absolutePath,
            'mime_type' => $mimeType,
            'extension' => $extension,
            'size_bytes' => $sizeBytes,
            'sha256' => hash_file('sha256', $absolutePath),
        ];
    }

    /**
     * Extensão pelo nome do arquivo. getExtension() do CI4 adivinha pelo MIME,
     * e o PHP não reconhece GLB/GLTF/STL (retorna vazio).
     */
    public static function extensionOf(UploadedFile $file): string
    {
        return strtolower($file->getClientExtension());
    }

    /** Confere o conteúdo: GLB começa com "glTF" v2; GLTF é JSON com "asset". */
    public static function isValidGltf(UploadedFile $file): bool
    {
        $tmp = $file->getTempName();
        $ext = self::extensionOf($file);
        if ($ext === 'glb') {
            $head = (string) file_get_contents($tmp, false, null, 0, 12);
            if (strlen($head) < 12 || substr($head, 0, 4) !== 'glTF') {
                return false;
            }
            $version = unpack('V', substr($head, 4, 4))[1];
            return $version === 2;
        }
        if ($ext === 'gltf') {
            $json = json_decode((string) file_get_contents($tmp), true);
            return is_array($json) && isset($json['asset']['version']);
        }
        return false;
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
