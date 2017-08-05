<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Log;

use App\Http\Requests\TagCrudRequest as StoreRequest;
use App\Http\Requests\TagCrudRequest as UpdateRequest;

class TagCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel('App\Models\Tag');
        $this->crud->setRoute('admin/tag');
        $this->crud->setEntityNameStrings('tag', 'tags');
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
                'name' => 'genre_id',
                'label' => 'Genre',
                'type' => 'select',
                'entity' => 'genre',
                'attribute' => 'title_en',
                'model' => 'App\Models\Genre',
            ],
        ]);
        $this->crud->addField([
			'name' => 'title_en',
			'label' => 'Title (English)',
		]);
        $this->crud->addField([
			'name' => 'title_th',
			'label' => 'Title (Thai)',
		]);
        $this->crud->addField([
			'name' => 'check_translation',
			'label' => 'Check Translation',
			'type' => 'checkbox',
		    'default' => '1',
		]);
        $this->crud->addField([
			'name' => 'image_path',
			'label' => 'Image',
		    'type' => 'browse',
		]);
        $this->crud->addField([
			'name' => 'rank',
			'label' => 'Rank',
		    'type' => 'number',
		    'default' => '0',
		]);
        $this->crud->addField([
        	'name' => 'genre_id',
        	'label' => 'Genre',
        	'type' => 'select',
        	'entity' => 'genre',
        	'attribute' => 'title_en',
        	'model' => 'App\Models\Genre',
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
