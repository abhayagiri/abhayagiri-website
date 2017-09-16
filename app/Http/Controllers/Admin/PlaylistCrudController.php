<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PlaylistCrudRequest as StoreRequest;
use App\Http\Requests\PlaylistCrudRequest as UpdateRequest;

class PlaylistCrudController extends AdminCrudController {

    public function setup()
    {
        $this->crud->setModel('App\Models\Playlist');
        $this->crud->setRoute('admin/playlists');
        $this->crud->orderBy('rank')->orderBy('title_en');
        $this->crud->setEntityNameStrings('playlist', 'playlists');
        $this->crud->allowAccess('revisions');
        $this->crud->with('revisionHistory');

        $this->addTrashedCrudFilter();

        $this->addImageCrudColumn();
        $this->addTitleEnCrudColumn();
        $this->addTitleThCrudColumn();
        $this->addCheckTranslationCrudColumn();
        $this->addLocalPostedAtCrudColumn();

        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->addDescriptionEnCrudField();
        $this->addDescriptionThCrudField();
        $this->addCheckTranslationCrudField();
        $this->addImageCrudField();
        $this->addRankCrudField();
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
