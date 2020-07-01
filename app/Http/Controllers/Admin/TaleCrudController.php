<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TaleRequest;
use App\Http\Controllers\Admin\Operations\RestoreOperation;

class TaleCrudController extends AdminCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\RevisionsOperation;
    use RestoreOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Tale');
        $this->crud->setRoute('admin/tales');
        $this->crud->setEntityNameStrings('tale', 'tales');
    }

    protected function setupListOperation()
    {
        $this->crud->orderBy('posted_at', 'desc');

        $this->addCheckTranslationCrudFilter();
        $this->addTrashedCrudFilter();

        $this->addAuthorCrudColumn();
        $this->addTitleEnCrudColumn();
        $this->addLocalPostedAtCrudColumn();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(TaleRequest::class);

        $this->addAuthorCrudField();
        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->addBodyEnCrudField();
        $this->addBodyThCrudField();
        $this->addImageCrudField();
        $this->addDraftCrudField();
        $this->addLocalPostedAtCrudField();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
