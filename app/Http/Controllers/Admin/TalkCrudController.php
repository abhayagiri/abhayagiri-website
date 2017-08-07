<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\TalkCrudRequest as StoreRequest;
use App\Http\Requests\TalkCrudRequest as UpdateRequest;

class TalkCrudController extends CrudController {

    public function setup() {
        $this->crud->setModel('App\Models\Talk');
        $this->crud->setRoute('admin/talks');
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
        $this->crud->orderBy('date', 'desc');
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
            'default' => Carbon::now(),
        ]);
        $this->crud->addField([
            'name' => 'recording_date',
            'label' => 'Recording Date',
            'type' => 'datetime',
            'default' => Carbon::now(),
        ]);
        $this->crud->addField([
            'name' => 'category',
            'label' => 'Category',
            'type' => 'select_from_array',
            'options' => $this->getCategoryOptions(),
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
            'disk' => 'audio',
        ]);
        $this->crud->addField([
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select_from_array',
            'options' => $this->getStatusOptions(),
        ]);
        $this->crud->addField([
            'name' => 'tags',
            'label' => 'Tags',
            'type' => 'select2_multiple',
            'entity' => 'tags',
            'attribute' => 'title_en',
            'model' => 'App\Models\Tag',
            'pivot' => true,
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

    protected function getCategoryOptions()
    {
        return $this->mapOptions(
            ['Dhamma Talk', 'Collection (.zip file)', 'Retreat', 'Question and Answer', 'Chanting']
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
