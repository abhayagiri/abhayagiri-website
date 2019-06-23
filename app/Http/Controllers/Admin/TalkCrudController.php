<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;

use App\Http\Requests\TalkCrudRequest as StoreRequest;
use App\Http\Requests\TalkCrudRequest as UpdateRequest;
use App\Models\Talk;
use App\Util;

class TalkCrudController extends AdminCrudController {

    public function setup() {
        $this->crud->setModel('App\Models\Talk');
        $this->crud->setRoute('admin/talks');
        $this->crud->setEntityNameStrings('talk', 'talks');
        $this->crud->setDefaultPageLength(100);
        $this->crud->orderBy('posted_at', 'desc');
        $this->crud->allowAccess('revisions');
        $this->crud->with('revisionHistory');

        $this->addCheckTranslationCrudFilter();
        $this->addTrashedCrudFilter();

        $this->addTitleEnCrudColumn();
        $this->addAuthorCrudColumn();
        $this->addLocalPostedAtCrudColumn();

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
            'name' => 'youtube_id',
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

    public function store(StoreRequest $request)
    {
        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }
}
