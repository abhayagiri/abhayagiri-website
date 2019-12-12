<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LanguageRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class LanguageCrudController extends AdminCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\RevisionsOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Language');
        $this->crud->setRoute('admin/languages');
        $this->crud->setEntityNameStrings('language', 'languages');
    }

    protected function setupListOperation()
    {
        $this->crud->orderBy('title_en');

        $this->addTrashedCrudFilter();

        $this->crud->addColumn([
            'label' => 'Code',
            'name' => 'code',
        ]);
        $this->addTitleEnCrudColumn();
        $this->addTitleThCrudColumn();
    }

    protected function setupCreateOperation()
    {   
        $this->crud->setValidation(LanguageRequest::class);

        $this->crud->addField([
            'label' => 'Code',
            'name' => 'code',
        ]);
        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
    }

    protected function setupUpdateOperation()
    {   
        $this->setupCreateOperation();
    }
}
