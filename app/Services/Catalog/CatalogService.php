<?php

declare(strict_types=1);

namespace App\Services\Catalog;

use App\Models\ModelFileModel;
use App\Models\ModelModel;

final class CatalogService
{
    public function __construct(
        private readonly ModelModel $models = new ModelModel(),
        private readonly ModelFileModel $files = new ModelFileModel(),
    ) {
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function searchPublished(?string $term = null): array
    {
        $builder = $this->models
            ->where('status', 'published')
            ->orderBy('created_at', 'DESC');

        if ($term !== null && trim($term) !== '') {
            $like = trim($term);
            $builder = $builder
                ->groupStart()
                ->like('title', $like)
                ->orLike('description', $like)
                ->orLike('category', $like)
                ->orLike('tags', $like)
                ->groupEnd();
        }

        return $builder->findAll();
    }

    /**
     * @return array{model: array<string, mixed>, preview: array<string, mixed>|null, files: list<array<string, mixed>>}|null
     */
    public function findPublishedBySlug(string $slug): ?array
    {
        $model = $this->models
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if ($model === null) {
            return null;
        }

        $files = $this->files
            ->where('model_id', $model['id'])
            ->where('version', $model['published_version'] ?? 1)
            ->where('status', 'approved')
            ->findAll();

        $preview = null;
        foreach ($files as $file) {
            if ($file['kind'] === 'preview') {
                $preview = $file;
                break;
            }
        }

        return [
            'model' => $model,
            'preview' => $preview,
            'files' => $files,
        ];
    }
}
