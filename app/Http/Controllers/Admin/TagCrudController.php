<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Log;

use App\Http\Requests\TagCrudRequest as StoreRequest;
use App\Http\Requests\TagCrudRequest as UpdateRequest;

class TagCrudController extends CrudController {

    use CommonCrudTrait;

    public function setup()
    {
        $this->crud->setModel('App\Models\Tag');
        $this->crud->setRoute('admin/tags');
        $this->crud->orderBy('title_en');
        $this->crud->setEntityNameStrings('tag', 'tags');
        $this->crud->allowAccess('revisions');
        $this->crud->with('revisionHistory');

        $this->addTrashedCrudFilter();

        $this->addTitleEnCrudColumn();
        $this->addTitleThCrudColumn();
        $this->addCheckTranslationCrudColumn();

        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->addCheckTranslationCrudField();
        $this->crud->addField([
            'name' => 'subjects',
            'label' => 'Subjects',
            'type' => 'select2_multiple',
            'entity' => 'subjects',
            'attribute' => 'title_en',
            'model' => 'App\Models\Subject',
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
