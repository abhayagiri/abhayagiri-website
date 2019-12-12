<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubjectGroupRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class SubjectGroupCrudController extends AdminCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\RevisionsOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\SubjectGroup');
        $this->crud->setRoute('admin/subject-groups');
        $this->crud->setEntityNameStrings('subject group', 'subject groups');
    }

    protected function setupListOperation()
    {
        $this->crud->orderBy('rank')->orderBy('title_en');

        $this->addTrashedCrudFilter();

        $this->addImageCrudColumn();
        $this->addTitleEnCrudColumn();
        $this->addTitleThCrudColumn();
        $this->addCheckTranslationCrudColumn();
    }

    protected function setupCreateOperation()
    {   
        $this->crud->setValidation(SubjectGroupRequest::class);

        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->addDescriptionEnCrudField();
        $this->addDescriptionThCrudField();
        $this->addCheckTranslationCrudField();
        $this->addImageCrudField();
        $this->addRankCrudField();
    }

    protected function setupUpdateOperation()
    {   
        $this->setupCreateOperation();
    }
}
