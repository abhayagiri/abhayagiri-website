<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LanguageCrudRequest as StoreRequest;
use App\Http\Requests\LanguageCrudRequest as UpdateRequest;

class LanguageCrudController extends AdminCrudController {

    public function setup()
    {
        $this->crud->setModel('App\Models\Language');
        $this->crud->setRoute('admin/languages');
        $this->crud->orderBy('title_en');
        $this->crud->setEntityNameStrings('language', 'languages');
        $this->crud->allowAccess('revisions');
        $this->crud->with('revisionHistory');

        $this->addTrashedCrudFilter();

        $this->crud->addColumn([
            'label' => 'Code',
            'name' => 'code',
        ]);
        $this->addTitleEnCrudColumn();
        $this->addTitleThCrudColumn();

        $this->crud->addField([
            'label' => 'Code',
            'name' => 'code',
        ]);
        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
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
