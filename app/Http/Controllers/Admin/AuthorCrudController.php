<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AuthorRequest;

class AuthorCrudController extends AdminCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\RevisionsOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Author');
        $this->crud->setRoute('admin/authors');
        $this->crud->setEntityNameStrings('author', 'authors');
    }

    protected function setupListOperation()
    {
        $this->crud->orderBy('rank')->orderBy('title_en');

        $this->addTrashedCrudFilter();

        $this->addImageCrudColumn();
        $this->addTitleEnCrudColumn();
        $this->addTitleThCrudColumn();
        $this->addBooleanCrudColumn('visiting', 'Visiting?');
        $this->addCheckTranslationCrudColumn();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(AuthorRequest::class);

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

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
