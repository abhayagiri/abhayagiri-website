<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\TalkCrudRequest as StoreRequest;
use App\Http\Requests\TalkCrudRequest as UpdateRequest;

class TalkCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel('App\Models\Talk');
        $this->crud->setRoute('admin/talk');
        $this->crud->setEntityNameStrings('talk', 'talks');
        $this->crud->enableAjaxTable(); // Large table
        $this->crud->setColumns(['title', 'author']);
        $this->crud->addColumn([
			'name' => 'date',
			'label' => 'Publish Date',
		    'type' => 'datetime',
		]);
        $this->crud->addColumn([
			'name' => 'recording_date',
			'label' => 'Recording Date',
		    'type' => 'datetime',
		]);
        $this->crud->addField([
			'name' => 'title',
			'label' => 'Title (English)',
		]);
        $this->crud->addField([
			'name' => 'author',
			'label' => 'Author',
			'type' => 'select_from_array',
			'options' => $this->getAuthorOptions(),
		]);
        $this->crud->addField([
			'name' => 'date',
			'label' => 'Publish Date',
		    'type' => 'datetime',
		]);
        $this->crud->addField([
			'name' => 'recording_date',
			'label' => 'Recording Date',
		    'type' => 'datetime',
		]);
        $this->crud->addField([
			'name' => 'language',
			'label' => 'Language',
			'type' => 'select_from_array',
			'options' => $this->getLanguageOptions(),
		]);
        $this->crud->addField([
			'name' => 'body',
			'label' => 'Description',
			'type' => 'summernote',
		]);
        $this->crud->addField([
			'name' => 'youtube_id',
			'label' => 'YouTube ID',
		]);
        $this->crud->addField([
			'name' => 'mp3',
			'label' => 'File',
			'type' => 'browse',
		]);
        $this->crud->addField([
			'name' => 'status',
			'label' => 'Status',
			'type' => 'select_from_array',
			'options' => $this->getStatusOptions(),
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

	protected function mapOptions($array)
	{
		$result = [];
		foreach ($array as $key) {
			$result[$key] = $key;
		}
		return $result;
	}

	protected function getAuthorOptions()
	{
		return $this->mapOptions(
			DB::table('authors')->orderBy('title')->pluck('title')
		);
	}

	protected function getLanguageOptions()
	{
		return $this->mapOptions(
			['English', 'Thai', 'English and Thai', 'English and Pali']
		);
	}

	protected function getStatusOptions()
	{
		return $this->mapOptions(['Open', 'Closed', 'Draft']);
	}
}
