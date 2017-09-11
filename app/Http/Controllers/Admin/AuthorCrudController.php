<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

use App\Http\Requests\AuthorCrudRequest as StoreRequest;
use App\Http\Requests\AuthorCrudRequest as UpdateRequest;

class AuthorCrudController extends CrudController {

    use CommonCrudTrait;

    public function setup()
    {
        $this->crud->setModel('App\Models\Author');
        $this->crud->setRoute('admin/authors');
        $this->crud->orderBy('title_en');
        $this->crud->setEntityNameStrings('author', 'authors');
        $this->crud->allowAccess('revisions');
        $this->crud->with('revisionHistory');

        $this->addImageCrudColumn();
        $this->addTitleEnCrudColumn();
        $this->addTitleThCrudColumn();
        $this->addCheckTranslationCrudColumn();

        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->addCheckTranslationCrudField();
        $this->addImageCrudField();
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
