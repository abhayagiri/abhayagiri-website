<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

use App\Http\Requests\TagCrudRequest as StoreRequest;
use App\Http\Requests\TagCrudRequest as UpdateRequest;

class TagCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel('App\Models\Tag');
        $this->crud->setRoute('admin/tag');
        $this->crud->setEntityNameStrings('tag', 'tags');
        $this->crud->addColumn([
			'name' => 'title_en',
			'label' => 'Title (English)',
		]);
        $this->crud->addColumn([
			'name' => 'title_th',
			'label' => 'Title (Thai)',
		]);
        $this->crud->addColumn([
        	'name' => 'genre_id',
        	'label' => 'Genre',
        	'type' => 'select',
        	'entity' => 'genre',
        	'attribute' => 'title_en',
        	'model' => 'App\Models\Genre',
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
		return parent::storeCrud();
	}

	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
