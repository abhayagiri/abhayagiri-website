<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TalkRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class TalkCrudController extends AdminCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\RevisionsOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Talk');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/talks');
        $this->crud->setEntityNameStrings('talk', 'talks');
    }

    protected function setupListOperation()
    {
        $this->crud->orderBy('posted_at', 'desc');

        $this->addCheckTranslationCrudFilter();
        $this->addTrashedCrudFilter();

        $this->addTitleEnCrudColumn();
        $this->addAuthorCrudColumn();
        $this->addLocalPostedAtCrudColumn();
    }

    protected function setupCreateOperation()
    {   
        $this->crud->setValidation(TalkRequest::class);

        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->addAuthorCrudField();
        $this->addLanguageCrudField();
        $this->crud->addField([
            'name' => 'playlists',
            'label' => 'Playlists',
            'type' => 'select2_multiple',
            'entity' => 'playlists',
            'attribute' => 'title_en',
            'model' => 'App\Models\Playlist',
            'pivot' => true,
        ]);
        $this->crud->addField([
            'name' => 'subjects',
            'label' => 'Subjects',
            'type' => 'select2_multiple',
            'entity' => 'subjects',
            'attribute' => 'title_en',
            'model' => 'App\Models\Subject',
            'pivot' => true,
        ]);
        $this->addDateCrudField('recorded_on', 'Recorded');
        $this->addDescriptionEnCrudField();
        $this->addDescriptionThCrudField();
        $this->addCheckTranslationCrudField();
        $this->crud->addField([
            'name' => 'youtube_video_id',
            'label' => 'YouTube ID',
            'hint' => 'YouTube URLs are also okay',
        ]);
        $this->addImageCrudField();
        $this->addUploadCrudField('media_path', 'Media File (MP3, etc.)');
        $this->crud->addField([
            'name' => 'hide_from_latest',
            'label' => 'Hide From Latest',
            'type' => 'checkbox',
            'default' => 0,
            'hint' => "Check this box if this talk shouldn't show up on the latest talks page (e.g. retreat talks).",
        ]);
        $this->addDraftCrudField();
        $this->addLocalPostedAtCrudField();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
