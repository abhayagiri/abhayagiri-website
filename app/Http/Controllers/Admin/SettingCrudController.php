<?php

// This file is based on:
// https://github.com/Laravel-Backpack/Settings/blob/master/src/app/Http/Controllers/SettingCrudController.php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SettingCrudRequest as StoreRequest;
use App\Http\Requests\SettingCrudRequest as UpdateRequest;

class SettingCrudController extends AdminCrudController
{
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
    ];

    public function __construct()
    {
        parent::__construct();

        $this->crud->setModel('App\Models\Setting');
        $this->crud->setRoute('admin/settings');
        $this->crud->setEntityNameStrings('setting', 'settings');
        $this->crud->orderBy('key');
        $this->crud->denyAccess(['create', 'delete']);
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
        $this->crud->addField([
            'name'       => 'key',
            'label'      => 'Key',
            'type'       => 'text',
            'attributes' => [
                'disabled' => 'disabled',
            ],
        ]);
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');

        $this->data['entry'] = $this->crud->getEntry($id);
        $this->addValueField($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    public function update(UpdateRequest $request)
    {
        $type = $this->getValueType($request->input('id'));
        if ($type === 'browse') {
            $value = $request->request->get('value');
            $request->request->remove('value');
            $request->request->set('value_media_path', $value);
        }
        return parent::updateCrud($request);
    }

    protected function getValueType($id)
    {
        $entry = $this->crud->getEntry($id);
        return array_get($this->fields, $entry->key, 'textarea');
    }

    protected function addValueField($id)
    {
        $type = $this->getValueType($id);
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
