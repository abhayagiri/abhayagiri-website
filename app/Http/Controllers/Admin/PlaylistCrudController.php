<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PlaylistRequest;
use App\Http\Controllers\Admin\Operations\RestoreOperation;

class PlaylistCrudController extends AdminCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\RevisionsOperation;
    use RestoreOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Playlist');
        $this->crud->setRoute('admin/playlists');
        $this->crud->setEntityNameStrings('playlist', 'playlists');
    }

    protected function setupListOperation()
    {
        $this->crud->orderBy('rank')->orderBy('title_en');

        $this->addTrashedCrudFilter();

        $this->addImageCrudColumn();
        $this->crud->addColumn([
            'name' => 'group_id',
            'label' => 'Group',
            'type' => 'select',
            'entity' => 'group',
            'attribute' => 'title_en',
            'model' => 'App\Models\PlaylistGroup',
        ]);
        $this->addTitleEnCrudColumn();
        $this->addTitleThCrudColumn();
        $this->addCheckTranslationCrudColumn();
        $this->addPostedAtCrudColumn();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PlaylistRequest::class);

        $this->crud->addField([
            'name' => 'group_id',
            'label' => 'Group',
            'type' => 'select',
            'entity' => 'group',
            'attribute' => 'title_en',
            'model' => 'App\Models\PlaylistGroup',
        ]);
        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->addDescriptionEnCrudField();
        $this->addDescriptionThCrudField();
        $this->addCheckTranslationCrudField();
        $this->crud->addField([
            'name' => 'youtube_playlist_id',
            'label' => 'YouTube Playlist ID',
            'hint' => 'YouTube Playlist URLs are also okay',
        ]);
        $this->addImageCrudField();
        $this->addRankCrudField();
        $this->addDraftCrudField();
        $this->addPostedAtCrudField();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
