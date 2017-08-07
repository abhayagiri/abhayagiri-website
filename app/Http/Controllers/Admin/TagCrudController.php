<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Log;

use App\Http\Requests\TagCrudRequest as StoreRequest;
use App\Http\Requests\TagCrudRequest as UpdateRequest;

class TagCrudController extends CrudController {

    public function setup() {
        $this->crud->setModel('App\Models\Tag');
        $this->crud->setRoute('admin/tags');
        $this->crud->orderBy('genre_id')->orderBy('rank')->orderBy('title_en');
        $this->crud->setEntityNameStrings('tag', 'tags');
        $this->crud->addColumns([
            [
                'name' => 'genre_id',
                'label' => 'Genre',
                'type' => 'select',
                'entity' => 'genre',
                'attribute' => 'title_en',
                'model' => 'App\Models\Genre',
            ],
            [
                'name' => 'rank',
                'label' => 'Rank',
            ],
            [
                'name' => 'image_path',
                'label' => 'Image',
                'type' => 'model_function',
                'function_name' => 'getIconHtml',
            ],
            [
                'name' => 'title_en',
                'label' => 'Title (English)',
            ],
            [
                'name' => 'title_th',
                'label' => 'Title (Thai)',
            ],
        ]);
        $this->crud->addFields([
            [
                'name' => 'genre_id',
                'label' => 'Genre',
                'type' => 'select',
                'entity' => 'genre',
                'attribute' => 'title_en',
                'model' => 'App\Models\Genre',
            ],
            [
                'name' => 'slug',
                'label' => 'Slug',
                'hint' => 'Short and unique name (for URLs)',
            ],
            [
                'name' => 'title_en',
                'label' => 'Title (English)',
            ],
            [
                'name' => 'title_th',
                'label' => 'Title (Thai)',
            ],
            [
                'name' => 'check_translation',
                'label' => 'Check Translation',
                'type' => 'checkbox',
                'default' => '1',
                'hint' => 'Check this box if this entry needs translation.',
            ],
            [
                'name' => 'image_path',
                'label' => 'Image',
                'type' => 'browse',
            ],
            [
                'name' => 'rank',
                'label' => 'Rank',
                'type' => 'number',
                'default' => '0',
                'hint' => 'Lower numbers are first, higher numbers are last.',
            ],
        ]);
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }
}
