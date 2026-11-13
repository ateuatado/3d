<?php

namespace App\Controllers;

use App\Services\Catalog\CatalogService;

class Home extends BaseController
{
    public function index(): string
    {
        $catalog = new CatalogService();

        return view('pages/home', [
            'title' => 'Modelos 3D para imprimir | 3d',
            'featuredModels' => $catalog->searchPublished(null, 6),
            'freeModels' => $catalog->searchPublished(null, 6, true),
        ]);
    }
}

