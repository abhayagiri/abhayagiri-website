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
        $this->crud->orderBy('date', 'desc');
        $this->crud->addColumns([
            [
                'name' => 'title',
                'label' => 'Title (English)',
            ],
            [
                'name' => 'author_id',
                'label' => 'Author',
                'type' => 'select',
                'entity' => 'author',
                'attribute' => 'title',
                'model' => 'App\Models\Author',
            ],
            [
                'name' => 'check_translation',
                'label' => 'Check Translation?',
                'type' => 'boolean',
            ],
            [
                'name' => 'date',
                'label' => 'Publish Date',
                'type' => 'datetime',
            ],
        ]);
        $this->crud->addFields([
            [
                'name' => 'title',
                'label' => 'Title (English)',
            ],
            [
                'name' => 'title_th',
                'label' => 'Title (Thai)',
            ],
            [
                'name' => 'author_id',
                'label' => 'Author',
                'type' => 'select',
                'entity' => 'author',
                'attribute' => 'title',
                'model' => 'App\Models\Author',
            ],
            [
                'name' => 'date',
                'label' => 'Publish Date',
                'type' => 'datetime',
                'default' => Carbon::now(),
            ],
            [
                'name' => 'recording_date',
                'label' => 'Recording Date',
                'type' => 'datetime',
                'default' => Carbon::now(),
            ],
             [
                'name' => 'type_id',
                'label' => 'Type',
                'type' => 'select',
                'entity' => 'talk_type',
                'attribute' => 'title_en',
                'model' => 'App\Models\TalkType',
            ],
            [
                'name' => 'language',
                'label' => 'Language',
                'type' => 'select_from_array',
                'options' => $this->getLanguageOptions(),
            ],
            [
                'name' => 'body',
                'label' => 'Description (English)',
                'type' => 'summernote',
            ],
            [
                'name' => 'description_th',
                'label' => 'Description (Thai)',
                'type' => 'summernote',
            ],
            [
                'name' => 'check_translation',
                'label' => 'Check Translation',
                'type' => 'checkbox',
                'default' => '1',
                'hint' => 'Check this box if this entry needs translation.',
            ],
            [
                'name' => 'youtube_id',
                'label' => 'YouTube ID',
            ],
            [
                'name' => 'mp3',
                'label' => 'File',
                'type' => 'browse',
                'disk' => 'audio',
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
            [
                'name' => 'playlists',
                'label' => 'Playlists',
                'type' => 'select2_multiple',
                'entity' => 'playlists',
                'attribute' => 'title_en',
                'model' => 'App\Models\Playlist',
                'pivot' => true,
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'select_from_array',
                'options' => $this->getStatusOptions(),
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

    protected function mapOptions($array)
    {
        $result = [];
        foreach ($array as $key) {
            $result[$key] = $key;
        }
        return $result;
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
