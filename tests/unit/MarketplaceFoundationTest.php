<?php

declare(strict_types=1);

use CodeIgniter\Test\CIUnitTestCase;
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
}
