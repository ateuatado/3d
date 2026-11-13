<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ModelFileModel;
use App\Models\ModelModel;
use App\Services\Audit\AuditService;
use App\Services\Storage\PrivateFileStorage;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Exceptions\PageNotFoundException;
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

    public function edit(int $id): string
    {
        $model = (new ModelModel())->find($id);
        if ($model === null) {
            throw PageNotFoundException::forPageNotFound('Modelo não encontrado.');
        }

        return view('admin/catalog/form', [
            'title' => 'Editar modelo | 3d',
            'model' => $model,
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
        $isFree = $this->request->getPost('is_free') === '1';
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
            'slug' => $this->slugFromTitle((string) $this->request->getPost('title')),
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
            'is_free' => $isFree ? 1 : 0,
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

    public function update(int $id): RedirectResponse
    {
        $model = (new ModelModel())->find($id);
        if ($model === null) {
            throw PageNotFoundException::forPageNotFound('Modelo não encontrado.');
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[180]',
            'description' => 'required|min_length[20]',
            'license_terms' => 'required|min_length[3]',
            'price' => 'required|decimal',
            'original_file' => 'permit_empty|max_size[original_file,512000]',
            'preview_file' => 'permit_empty|max_size[preview_file,10240]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $filesConfig = new FilesConfig();
        $fileModel = new ModelFileModel();
        $preview = $this->request->getFile('preview_file');
        $original = $this->request->getFile('original_file');
        $hasPreview = $fileModel->where(['model_id' => $id, 'kind' => 'preview', 'status' => 'approved'])->countAllResults() > 0;
        $hasOriginal = $fileModel->where(['model_id' => $id, 'kind' => 'original', 'status' => 'approved'])->countAllResults() > 0;
        $previewSent = $preview?->isValid() ?? false;
        $publish = $this->request->getPost('publish') === '1';
        $isFree = $this->request->getPost('is_free') === '1';

        if ($publish && ! ($hasPreview || $previewSent) || $publish && ! ($hasOriginal || $original?->isValid())) {
            return redirect()->back()->withInput()->with('errors', [
                'publish' => 'Para publicar, o modelo precisa de um arquivo original e uma pré-visualização aprovados.',
            ]);
        }
        if ($previewSent && (! in_array(PrivateFileStorage::extensionOf($preview), $filesConfig->previewExtensions, true) || ! PrivateFileStorage::isValidGltf($preview))) {
            return redirect()->back()->withInput()->with('errors', ['preview_file' => 'A pré-visualização deve ser um GLB ou GLTF válido.']);
        }
        if ($publish && ! auth()->user()?->can('catalog.publish')) {
            return redirect()->back()->withInput()->with('errors', ['publish' => 'Você não possui permissão para publicar modelos.']);
        }

        $newStatus = $publish ? 'published' : ($model['status'] === 'published' ? 'draft' : $model['status']);
        $version = max(1, (int) ($model['published_version'] ?? 1));
        $modelModel = new ModelModel();
        $modelModel->update($id, [
            'title' => trim((string) $this->request->getPost('title')),
            'description' => trim((string) $this->request->getPost('description')),
            'category' => trim((string) $this->request->getPost('category')),
            'tags' => trim((string) $this->request->getPost('tags')),
            'dimensions' => trim((string) $this->request->getPost('dimensions')),
            'unit' => trim((string) $this->request->getPost('unit')),
            'intended_use' => trim((string) $this->request->getPost('intended_use')),
            'compatibility_notes' => trim((string) $this->request->getPost('compatibility_notes')),
            'license_terms' => trim((string) $this->request->getPost('license_terms')),
            'price' => (float) $this->request->getPost('price'),
            'status' => $newStatus,
            'published_version' => $publish ? $version : $model['published_version'],
            'updated_by' => auth()->user()?->id,
            'is_free' => $isFree ? 1 : 0,
        ]);

        $storage = new PrivateFileStorage($filesConfig);
        $path = 'models/' . $id . '/v' . $version;
        if ($original?->isValid()) {
            $data = $storage->store($original, $path, $filesConfig->modelExtensions, $filesConfig->maxModelFileSize);
            $fileModel->insert($data + ['model_id' => $id, 'version' => $version, 'kind' => 'original', 'status' => 'approved']);
        }
        if ($previewSent) {
            $data = $storage->store($preview, $path, $filesConfig->previewExtensions, $filesConfig->maxModelFileSize);
            $fileModel->insert($data + ['model_id' => $id, 'version' => $version, 'kind' => 'preview', 'status' => 'approved']);
        }

        (new AuditService())->record('catalog.model.updated', 'model', $id, 'success', [
            'status' => $newStatus,
        ], auth()->user()?->id);

        return redirect()->to(site_url('admin/catalogo'))->with('message', 'Modelo atualizado com sucesso.');
    }

    public function archive(int $id): RedirectResponse
    {
        $model = new ModelModel();
        if ($model->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('Modelo não encontrado.');
        }

        $model->update($id, ['status' => 'archived', 'updated_by' => auth()->user()?->id]);
        (new AuditService())->record('catalog.model.archived', 'model', $id, 'success', [], auth()->user()?->id);

        return redirect()->to(site_url('admin/catalogo'))->with('message', 'Modelo arquivado.');
    }

    private function slugFromTitle(string $title): string
    {
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', trim($title));
        $base = strtolower((string) preg_replace('/[^a-zA-Z0-9]+/', '-', $ascii ?: $title));
        $base = trim($base, '-');

        return ($base !== '' ? $base : 'modelo') . '-' . bin2hex(random_bytes(3));
    }
}
