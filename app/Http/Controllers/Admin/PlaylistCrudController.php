<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Backpack\CRUD\app\Http\Controllers\CrudController;

use App\Http\Requests\PlaylistCrudRequest as StoreRequest;
use App\Http\Requests\PlaylistCrudRequest as UpdateRequest;

class PlaylistCrudController extends CrudController {

    use CommonCrudTrait;

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

        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->addDescriptionEnCrudField();
        $this->addDescriptionThCrudField();
        $this->addCheckTranslationCrudField();
        $this->addImageCrudField();
        $this->addDateTimeCrudField('published_at', 'Published');
        $this->addRankCrudField();
        $this->crud->addField([
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select_from_array',
            'options' => ['open', 'closed'],
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
}
