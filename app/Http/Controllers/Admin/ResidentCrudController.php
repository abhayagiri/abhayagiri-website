<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ResidentCrudRequest as StoreRequest;
use App\Http\Requests\ResidentCrudRequest as UpdateRequest;

class ResidentCrudController extends AdminCrudController {

    public function setup()
    {
        $this->crud->setModel('App\Models\Resident');
        $this->crud->setRoute('admin/residents');
        $this->crud->orderBy('rank')->orderBy('title_en');
        $this->crud->setEntityNameStrings('resident', 'residents');
        $this->crud->setDefaultPageLength(100);
        $this->crud->allowAccess('revisions');
        $this->crud->with('revisionHistory');

        $this->addCheckTranslationCrudFilter();
        $this->addTrashedCrudFilter();

        $this->addImageCrudColumn();
        $this->addTitleEnCrudColumn();
        $this->addTitleThCrudColumn();
        $this->addRankCrudColumn();
        $this->addCheckTranslationCrudColumn();
        $this->crud->addColumn([
            'name' => 'status',
            'label' => 'Status',
        ]);

        $this->crud->addField([
            'name' => 'slug',
            'label' => 'Slug',
            'hint' => 'Simple name for URLs (use lowercase; no title, spaces or diacritics).'
        ]);
        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->addDescriptionEnCrudField();
        $this->addDescriptionThCrudField();
        $this->addCheckTranslationCrudField();
        $this->addImageCrudField();
        $this->addRankCrudField();
        $this->crud->addField([
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select_from_array',
            'options' => $this->getStatusCrudFieldOptions(),
            'allows_null' => true,
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

    protected function getStatusCrudFieldOptions()
    {
        return [
            'current' => 'Current',
            'traveling' => 'Traveling',
            'former' => 'Former (unlisted)',
        ];
    }
}
