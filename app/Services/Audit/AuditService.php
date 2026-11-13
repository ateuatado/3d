<?php

declare(strict_types=1);

namespace App\Services\Audit;

use App\Models\AuditEventModel;
use CodeIgniter\HTTP\RequestInterface;
use Config\Services;

final class AuditService
{
    public function __construct(
        private readonly AuditEventModel $events = new AuditEventModel(),
    ) {
    }

    public function record(
        string $action,
        string $entity,
        string|int|null $entityId = null,
        string $result = 'success',
        array $metadata = [],
        ?int $userId = null,
    ): int {
        $request = Services::request();
        $safeMetadata = $this->removeSecrets($metadata);

        return (int) $this->events->insert([
            'user_id' => $userId,
            'action' => $action,
            'entity' => $entity,
            'entity_id' => $entityId === null ? null : (string) $entityId,
            'result' => $result,
            'metadata' => $safeMetadata === [] ? null : json_encode($safeMetadata, JSON_THROW_ON_ERROR),
            'ip_address' => $request instanceof RequestInterface ? $request->getIPAddress() : null,
            'correlation_id' => bin2hex(random_bytes(16)),
            'created_at' => date('Y-m-d H:i:s'),
        ], true);
    }

    private function removeSecrets(array $metadata): array
    {
        foreach (['token', 'access_token', 'refresh_token', 'client_secret', 'password', 'secret'] as $key) {
            unset($metadata[$key]);
        }

        return $metadata;
    }
}
