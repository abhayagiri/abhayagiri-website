<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubjectCrudRequest as StoreRequest;
use App\Http\Requests\SubjectCrudRequest as UpdateRequest;

class SubjectCrudController extends AdminCrudController {

    public function setup()
    {
        $this->crud->setModel('App\Models\Subject');
        $this->crud->setRoute('admin/subjects');
        $this->crud->orderBy('group_id')->orderBy('rank')->orderBy('title_en');
        $this->crud->setEntityNameStrings('subject', 'subjects');
        $this->crud->allowAccess('revisions');
        $this->crud->with('revisionHistory');

        $this->addTrashedCrudFilter();

        $this->addImageCrudColumn();
        $this->crud->addColumn([
            'name' => 'group_id',
            'label' => 'Group',
            'type' => 'select',
            'entity' => 'group',
            'attribute' => 'title_en',
            'model' => 'App\Models\SubjectGroup',
        ]);
        $this->addTitleEnCrudColumn();
        $this->addTitleThCrudColumn();
        $this->addCheckTranslationCrudColumn();

        $this->crud->addField([
            'name' => 'group_id',
            'label' => 'Group',
            'type' => 'select',
            'entity' => 'group',
            'attribute' => 'title_en',
            'model' => 'App\Models\SubjectGroup',
        ]);
        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->addDescriptionEnCrudField();
        $this->addDescriptionThCrudField();
        $this->addCheckTranslationCrudField();
        $this->addImageCrudField();
        $this->addRankCrudField();
        $this->crud->addField([
            'name' => 'tags',
            'label' => 'Tags',
            'type' => 'select2_multiple',
            'entity' => 'tags',
            'attribute' => 'title_en',
            'model' => 'App\Models\Tag',
            'pivot' => true,
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
