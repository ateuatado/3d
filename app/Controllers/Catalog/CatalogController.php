<?php

declare(strict_types=1);

namespace App\Controllers\Catalog;

use App\Controllers\BaseController;
use App\Services\Catalog\CatalogService;
use App\Models\ModelFileModel;
use App\Services\Storage\PrivateFileStorage;
use CodeIgniter\Exceptions\PageNotFoundException;

final class CatalogController extends BaseController
{
    public function index(): string
    {
        $term = trim((string) $this->request->getGet('q'));

        return view('catalog/index', [
            'title' => $term === '' ? 'Catálogo de modelos 3D' : 'Busca: ' . $term,
            'term' => $term,
            'models' => (new CatalogService())->searchPublished($term),
        ]);
    }

    public function show(string $slug): string
    {
        $product = (new CatalogService())->findPublishedBySlug($slug);
        if ($product === null) {
            throw PageNotFoundException::forPageNotFound('Modelo não encontrado.');
        }

        return view('catalog/show', [
            'title' => $product['model']['title'] . ' | 3d',
            ...$product,
        ]);
    }

    public function preview(int $fileId)
    {
        $file = (new ModelFileModel())
            ->where('id', $fileId)
            ->where('kind', 'preview')
            ->where('status', 'approved')
            ->first();

        if ($file === null) {
            throw PageNotFoundException::forPageNotFound('Pré-visualização não encontrada.');
        }

        (new PrivateFileStorage())->assertPrivatePath($file['storage_path']);

        return $this->response
            ->download($file['storage_path'], null)
            ->setContentType($file['mime_type']);
    }
}
