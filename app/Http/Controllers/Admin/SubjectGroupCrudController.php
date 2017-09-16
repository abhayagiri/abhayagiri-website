<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubjectGroupCrudRequest as StoreRequest;
use App\Http\Requests\SubjectGroupCrudRequest as UpdateRequest;

class SubjectGroupCrudController extends AdminCrudController {

    public function setup()
    {
        $this->crud->setModel('App\Models\SubjectGroup');
        $this->crud->setRoute('admin/subject-groups');
        $this->crud->orderBy('rank')->orderBy('title_en');
        $this->crud->setEntityNameStrings('subject group', 'subject groups');
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
