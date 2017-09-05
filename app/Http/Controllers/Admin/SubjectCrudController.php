<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

use App\Http\Requests\SubjectCrudRequest as StoreRequest;
use App\Http\Requests\SubjectCrudRequest as UpdateRequest;

class SubjectCrudController extends CrudController {

    public function setup() {
        $this->crud->setModel('App\Models\Subject');
        $this->crud->setRoute('admin/subjects');
        $this->crud->orderBy('group_id')->orderBy('rank')->orderBy('title_en');
        $this->crud->setEntityNameStrings('subject', 'subjects');
        $this->crud->addColumns([
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
            [
                'name' => 'group_id',
                'label' => 'Group',
                'type' => 'select',
                'entity' => 'group',
                'attribute' => 'title_en',
                'model' => 'App\Models\SubjectGroup',
            ],
            [
                'name' => 'check_translation',
                'label' => 'Check Translation?',
                'type' => 'boolean',
            ],
        ]);
        $this->crud->addFields([
            [
                'name' => 'group_id',
                'label' => 'Group',
                'type' => 'select',
                'entity' => 'group',
                'attribute' => 'title_en',
                'model' => 'App\Models\SubjectGroup',
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
                'name' => 'description_en',
                'label' => 'Description (English)',
                'type' => 'simplemde',
            ],
            [
                'name' => 'description_th',
                'label' => 'Description (Thai)',
                'type' => 'simplemde',
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
            [
                'name' => 'tags',
                'label' => 'Tags',
                'type' => 'select2_multiple',
                'entity' => 'tags',
                'attribute' => 'title_en',
                'model' => 'App\Models\Tag',
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
