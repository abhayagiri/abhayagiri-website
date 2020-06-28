<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubjectRequest;
use App\Http\Controllers\Admin\Operations\RestoreOperation;

class SubjectCrudController extends AdminCrudController
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
        $this->crud->setModel('App\Models\Subject');
        $this->crud->setRoute('admin/subjects');
        $this->crud->setEntityNameStrings('subject', 'subjects');
    }

    protected function setupListOperation()
    {
        $this->crud->orderBy('group_id')->orderBy('rank')->orderBy('title_en');

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
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(SubjectRequest::class);

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
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
