<?php

declare(strict_types=1);

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\HTTP\Files\UploadedFile;
use App\Services\Storage\PrivateFileStorage;
use Config\AuthGroups;
use Config\Files;

/**
 * @internal
 */
final class MarketplaceFoundationTest extends CIUnitTestCase
{
    public function testMarketplaceGroupsAndPermissionsExist(): void
    {
        $config = new AuthGroups();

        $this->assertArrayHasKey('catalog', $config->groups);
        $this->assertArrayHasKey('support', $config->groups);
        $this->assertArrayHasKey('catalog.manage', $config->permissions);
        $this->assertContains('catalog.*', $config->matrix['admin']);
    }

    public function testPrivateStorageIsOutsidePublicDirectory(): void
    {
        $config = new Files();

        $this->assertStringStartsWith(WRITEPATH, $config->privateRoot);
        $this->assertStringNotContainsString(DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR, $config->privateRoot);
    }

    public function testCommonModelFormatsAreConfigured(): void
    {
        $config = new Files();

        $this->assertContains('stl', $config->modelExtensions);
        $this->assertContains('obj', $config->modelExtensions);
        $this->assertContains('glb', $config->previewExtensions);
    }

    public function testPrivateStorageReadsMetadataBeforeMovingTemporaryUpload(): void
    {
        $temporary = tempnam(WRITEPATH, 'upload_');
        file_put_contents($temporary, 'solid test');
        $config = new Files();
        $config->privateRoot = WRITEPATH . 'tests-private';
        $file = new class($temporary, 'modelo.stl', 'application/octet-stream', 10, UPLOAD_ERR_OK) extends UploadedFile {
            public function isValid(): bool
            {
                return true;
            }

            public function move(string $targetPath, ?string $name = null, bool $overwrite = false)
            {
                $destination = rtrim($targetPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $name;
                rename($this->path, $destination);
                $this->path .= '.moved';
                $this->hasMoved = true;

                return true;
            }
        };

        try {
            $stored = (new PrivateFileStorage($config))->store(
                $file,
                'models/test',
                $config->modelExtensions,
                $config->maxModelFileSize,
            );

            $this->assertSame('modelo.stl', $stored['original_name']);
            $this->assertSame('stl', $stored['extension']);
            $this->assertFileExists($stored['storage_path']);
        } finally {
            if (isset($stored['storage_path']) && is_file($stored['storage_path'])) {
                unlink($stored['storage_path']);
            }
            if (is_dir($config->privateRoot . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'test')) {
                rmdir($config->privateRoot . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'test');
            }
            if (is_dir($config->privateRoot . DIRECTORY_SEPARATOR . 'models')) {
                rmdir($config->privateRoot . DIRECTORY_SEPARATOR . 'models');
            }
            if (is_dir($config->privateRoot)) {
                rmdir($config->privateRoot);
            }
        }
    }
}
