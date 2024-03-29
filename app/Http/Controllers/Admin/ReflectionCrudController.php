<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReflectionRequest;
use App\Http\Controllers\Admin\Operations\RestoreOperation;

class ReflectionCrudController extends AdminCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\ReviseOperation\ReviseOperation;
    use RestoreOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Reflection');
        $this->crud->setRoute('admin/reflections');
        $this->crud->setEntityNameStrings('reflection', 'reflections');
    }

    protected function setupListOperation()
    {
        $this->crud->orderBy('posted_at', 'desc');

        $this->addCheckTranslationCrudFilter();
        $this->addTrashedCrudFilter();

        $this->addAuthorCrudColumn();
        $this->addTitleCrudColumn();
        $this->addPostedAtCrudColumn();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ReflectionRequest::class);

        $this->addLanguageCrudField();
        $this->addAuthorCrudField();
        $this->addTitleCrudField();
        $this->addAltTitleEnCrudField();
        $this->addAltTitleThCrudField();
        $this->addCheckTranslationCrudField();
        $this->addBodyCrudField();
        $this->addImageCrudField();
        $this->addDraftCrudField();
        $this->addPostedAtCrudField();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
