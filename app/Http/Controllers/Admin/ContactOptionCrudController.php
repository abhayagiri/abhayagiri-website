<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContactOptionRequest;
use App\Http\Controllers\Admin\Operations\RestoreOperation;

class ContactOptionCrudController extends AdminCrudController
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
        $this->crud->setModel('App\Models\ContactOption');
        $this->crud->setRoute('admin/contact-options');
        $this->crud->setEntityNameStrings('contact option', 'contact options');
    }

    protected function setupListOperation()
    {
        $this->crud->orderBy('rank')->orderBy('slug', 'desc');

        $this->addStringCrudColumn('name_en', 'Name (English)');
        $this->addStringCrudColumn('name_th', 'Name (Thai)');
        $this->addStringCrudColumn('slug', 'Slug');
        $this->addStringCrudColumn('email', 'Email');
        $this->addBooleanCrudColumn('active', 'Active?');
        $this->addBooleanCrudColumn('published', 'Published?');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ContactOptionRequest::class);

        $this->addStringCrudField('name_en', 'Name (English)');
        $this->addStringCrudField('name_th', 'Name (Thai)');
        $this->addStringCrudField('slug', 'Slug');
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
