<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

use App\Http\Requests\GenreCrudRequest as StoreRequest;
use App\Http\Requests\GenreCrudRequest as UpdateRequest;

class GenreCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel('App\Models\Genre');
        $this->crud->setRoute('admin/genre');
        $this->crud->setEntityNameStrings('genre', 'genres');
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
