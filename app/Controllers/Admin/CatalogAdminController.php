<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ModelFileModel;
use App\Models\ModelModel;
use App\Services\Audit\AuditService;
use App\Services\Storage\PrivateFileStorage;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Files as FilesConfig;

final class CatalogAdminController extends BaseController
{
    public function index(): string
    {
        return view('admin/catalog/index', [
            'title' => 'Catálogo administrativo | 3d',
            'models' => (new ModelModel())->orderBy('created_at', 'DESC')->findAll(),
        ]);
    }

    public function new(): string
    {
        return view('admin/catalog/form', [
            'title' => 'Novo modelo | 3d',
            'model' => null,
            'errors' => session('errors') ?? [],
        ]);
    }

    public function create(): RedirectResponse
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[180]',
            'description' => 'required|min_length[20]',
            'license_terms' => 'required|min_length[3]',
            'price' => 'required|decimal',
            'original_file' => 'uploaded[original_file]|max_size[original_file,512000]',
            'preview_file' => 'permit_empty|max_size[preview_file,10240]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $filesConfig = new FilesConfig();
        $original = $this->request->getFile('original_file');
        $preview = $this->request->getFile('preview_file');
        $publish = $this->request->getPost('publish') === '1';
        $previewOk = $preview !== null && $preview->isValid()
            && in_array(PrivateFileStorage::extensionOf($preview), $filesConfig->previewExtensions, true)
            && PrivateFileStorage::isValidGltf($preview);
        if ($publish && ! $previewOk) {
            return redirect()->back()->withInput()->with('errors', [
                'preview_file' => 'Para publicar, envie uma pré-visualização GLB ou GLTF válida.',
            ]);
        }

        $user = auth()->user();
        if ($publish && ! $user?->can('catalog.publish')) {
            return redirect()->back()->withInput()->with('errors', [
                'publish' => 'Você não possui permissão para publicar modelos.',
            ]);
        }

        $modelId = (new ModelModel())->insert([
            'title' => trim((string) $this->request->getPost('title')),
            'slug' => url_title((string) $this->request->getPost('title'), '-', true) . '-' . bin2hex(random_bytes(3)),
            'description' => trim((string) $this->request->getPost('description')),
            'category' => trim((string) $this->request->getPost('category')),
            'tags' => trim((string) $this->request->getPost('tags')),
            'dimensions' => trim((string) $this->request->getPost('dimensions')),
            'unit' => trim((string) $this->request->getPost('unit')),
            'intended_use' => trim((string) $this->request->getPost('intended_use')),
            'compatibility_notes' => trim((string) $this->request->getPost('compatibility_notes')),
            'license_terms' => trim((string) $this->request->getPost('license_terms')),
            'price' => (float) $this->request->getPost('price'),
            'currency' => 'BRL',
            'status' => $publish ? 'published' : 'draft',
            'published_version' => $publish ? 1 : null,
            'created_by' => $user?->id,
            'updated_by' => $user?->id,
        ], true);

        if (! $modelId) {
            return redirect()->back()->withInput()->with('error', 'Não foi possível criar o modelo.');
        }

        $storage = new PrivateFileStorage($filesConfig);
        $modelFiles = new ModelFileModel();
        $path = 'models/' . $modelId . '/v1';
        $originalData = $storage->store($original, $path, $filesConfig->modelExtensions, $filesConfig->maxModelFileSize);
        $modelFiles->insert($originalData + [
            'model_id' => $modelId, 'version' => 1, 'kind' => 'original', 'status' => 'approved',
        ]);

        if ($preview?->isValid()) {
            $previewData = $storage->store($preview, $path, $filesConfig->previewExtensions, $filesConfig->maxModelFileSize);
            $modelFiles->insert($previewData + [
                'model_id' => $modelId, 'version' => 1, 'kind' => 'preview', 'status' => 'approved',
            ]);
        }

        (new AuditService())->record('catalog.model.created', 'model', $modelId, 'success', [
            'published' => $publish,
        ], $user?->id);

        return redirect()->to(site_url('admin/catalogo'))->with('message', 'Modelo criado com sucesso.');
    }
}
