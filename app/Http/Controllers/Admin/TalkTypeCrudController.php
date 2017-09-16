<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TalkTypeCrudRequest as StoreRequest;
use App\Http\Requests\TalkTypeCrudRequest as UpdateRequest;

class TalkTypeCrudController extends AdminCrudController {

    public function setup()
    {
        $this->crud->setModel('App\Models\TalkType');
        $this->crud->setRoute('admin/talk-types');
        $this->crud->orderBy('rank')->orderBy('title_en');
        $this->crud->setEntityNameStrings('talk type', 'talk types');
        $this->crud->allowAccess('revisions');
        $this->crud>with('revisionHistory');

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
        $this->addRankCrudField();
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
