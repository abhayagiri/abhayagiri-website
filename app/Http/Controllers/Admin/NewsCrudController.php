<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;

use App\Http\Requests\NewsCrudRequest as StoreRequest;
use App\Http\Requests\NewsCrudRequest as UpdateRequest;
use App\Models\News;
use App\Util;

class NewsCrudController extends CrudController {

    use CommonCrudTrait;

    public function setup() {
        $this->crud->setModel('App\Models\News');
        $this->crud->setRoute('admin/news');
        $this->crud->setEntityNameStrings('news', 'news');
        $this->crud->orderBy('posted_at', 'desc');
        //$this->crud->allowAccess('revisions');
        //$this->crud->with('revisionHistory');

        $this->addCheckTranslationCrudFilter();
        $this->addTrashedCrudFilter();

        $this->addTitleEnCrudColumn();
        $this->addLocalPostedAtCrudColumn();

        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->addBodyEnCrudField();
        $this->addBodyThCrudField();
        $this->addCheckTranslationCrudField();
        $this->addImageCrudField();
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
