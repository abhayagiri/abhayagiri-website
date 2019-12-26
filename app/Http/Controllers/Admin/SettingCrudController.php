<?php

// This file is based on:
// https://github.com/Laravel-Backpack/Settings/blob/master/src/app/Http/Controllers/SettingCrudController.php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SettingRequest;

class SettingCrudController extends AdminCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as parentUpdate;
    }
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\RevisionsOperation;

    protected $fields = [
        'books.request_form_en' => 'markdown',
        'books.request_form_th' => 'markdown',
        'home.news.count' => 'number',
        'talks.latest.main.count' => 'number',
        'talks.latest.main.playlist_group_id' => 'playlist_group',
        'talks.latest.alt.count' => 'number',
        'talks.latest.alt.playlist_group_id' => 'playlist_group',
        'talks.latest.search.image_file' => 'browse',
        'talks.latest.search.description_en' => 'text',
        'talks.latest.search.description_th' => 'text',
        'talks.latest.authors.image_file' => 'browse',
        'talks.latest.authors.description_en' => 'text',
        'talks.latest.authors.description_th' => 'text',
        'talks.latest.playlists.image_file' => 'browse',
        'talks.latest.playlists.description_en' => 'text',
        'talks.latest.playlists.description_th' => 'text',
        'talks.latest.subjects.image_file' => 'browse',
        'talks.latest.subjects.description_en' => 'text',
        'talks.latest.subjects.description_th' => 'text',
        'contact.preamble_en' => 'textarea',
        'contact.preamble_th' => 'textarea',
        'authors.default_image_file' => 'browse',
        'books.default_image_file' => 'browse',
        'news.default_image_file' => 'browse',
        'playlists.default_image_file' => 'browse',
        'playlistgroups.default_image_file' => 'browse',
        'reflections.default_image_file' => 'browse',
        'residents.default_image_file' => 'browse',
        'subjects.default_image_file' => 'browse',
        'subjectgroups.default_image_file' => 'browse',
        'talks.default_image_file' => 'browse',
    ];

    public function setup()
    {
        $this->crud->setModel('App\Models\Setting');
        $this->crud->setRoute('admin/settings');
        $this->crud->setEntityNameStrings('setting', 'settings');
    }

    protected function setupListOperation()
    {
        $this->crud->setDefaultPageLength(100);

        $this->crud->setColumns([
            [
                'name'  => 'key',
                'label' => 'Key',
            ],
            [
                'name'  => 'value',
                'label' => 'Value',
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(SettingRequest::class);

        $this->crud->addField([
            'name'       => 'key',
            'label'      => 'Key',
            'type'       => 'text',
            'attributes' => [
                'disabled' => 'disabled',
            ],
        ]);
    }
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
        $this->addValueField();
    }

    /**
     * Update the specified resource in the database.
     *
     * @return Response
     */
    public function update()
    {
        $type = $this->getValueType();
        if ($type === 'browse') {
            $request = $this->request->request;
            $value = $request->get('value');
            $request->remove('value');
            $request->set('value_media_path', $value);
            $this->crud->addField(['name' => 'value_media_path']);
        }
        return $this->parentUpdate();
    }

    protected function getValueType()
    {
        $entry = $this->crud->getCurrentEntry();
        return array_get($this->fields, $entry->key, 'textarea');
    }

    protected function addValueField()
    {
        $type = $this->getValueType();
        $method = camel_case('add_' . $type . '_value_field');
        if (method_exists($this, $method)) {
            call_user_func([$this, $method]);
        } else {
            $this->addCommonValueField($type);
        }
    }

    protected function addCommonValueField($type)
    {
        $this->crud->addField([
            'name' => 'value',
            'label' => 'Value',
            'type' => $type,
        ]);
    }

    protected function addMarkdownValueField()
    {
        $this->addMarkdownCrudField('value', 'Value');
    }

    protected function addPlaylistGroupValueField()
    {
        $this->crud->addField([
            'name' => 'value',
            'label' => 'Value',
            'type' => 'select',
            'entity' => 'playlist',
            'attribute' => 'title_en',
            'model' => 'App\Models\PlaylistGroup',
        ]);
    }
}
