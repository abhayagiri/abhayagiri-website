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
        $this->crud->orderBy('title_en');
        $this->crud->setEntityNameStrings('tag', 'tags');
        $this->crud->addColumns([
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
                'label' => 'Check Translation?',
                'type' => 'boolean',
            ],
        ]);
        $this->crud->addFields([
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
                'name' => 'subjects',
                'label' => 'Subjects',
                'type' => 'select2_multiple',
                'entity' => 'subjects',
                'attribute' => 'title_en',
                'model' => 'App\Models\Subject',
                'pivot' => true,
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
