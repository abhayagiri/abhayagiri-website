<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContactOptionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class ContactOptionCrudController extends AdminCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\RevisionsOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\ContactOption');
        $this->crud->setRoute('admin/contact-options');
        $this->crud->setEntityNameStrings('contact option', 'contact options');
    }

    protected function setupListOperation()
    {
        $this->crud->orderBy('rank')->orderBy('slug', 'desc');

        $this->addStringCrudColumn('name_en', 'Name (English)');
        $this->addStringCrudColumn('name_th', 'Name (Thai)');
        $this->addStringCrudColumn('email', 'Email');
        $this->addBooleanCrudColumn('active', 'Active?');
        $this->addBooleanCrudColumn('published', 'Published?');
    }

    protected function setupCreateOperation()
    {   
        $this->crud->setValidation(ContactOptionRequest::class);

        $this->addStringCrudField('name_en', 'Name (English)');
        $this->addStringCrudField('name_th', 'Name (Thai)');
        $this->addBodyEnCrudField();
        $this->addBodyThCrudField();
        $this->addConfirmationEnCrudField();
        $this->addConfirmationThCrudField();
        $this->addStringCrudField('email', 'Email');
        $this->addActiveCrudField();
        $this->addPublishedCrudField();
        $this->addRankCrudField();
    }

    protected function setupUpdateOperation()
    {   
        $this->setupCreateOperation();
    }
}
