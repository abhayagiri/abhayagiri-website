<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AuthorCrudRequest as StoreRequest;
use App\Http\Requests\AuthorCrudRequest as UpdateRequest;

class AuthorCrudController extends AdminCrudController
{
    public function setup()
    {
        $this->crud->setModel('App\Models\Author');
        $this->crud->setRoute('admin/authors');
        $this->crud->orderBy('rank')->orderBy('title_en');
        $this->crud->setEntityNameStrings('author', 'authors');
        $this->crud->allowAccess('revisions');
        $this->crud->with('revisionHistory');

        $this->addTrashedCrudFilter();

        $this->addImageCrudColumn();
        $this->addTitleEnCrudColumn();
        $this->addTitleThCrudColumn();
        $this->addBooleanCrudColumn('visiting', 'Visiting?');
        $this->addCheckTranslationCrudColumn();

        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->crud->addField([
            'name' => 'visiting',
            'label' => 'Visiting',
            'type' => 'checkbox',
            'default' => '0',
            'hint' => 'Check this box if this author is a visiting Ajahn.',
        ]);
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
