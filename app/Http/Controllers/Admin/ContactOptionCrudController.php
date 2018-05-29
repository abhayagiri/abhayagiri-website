<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContactOptionCrudRequest as StoreRequest;
use App\Http\Requests\ContactOptionCrudRequest as UpdateRequest;

class ContactOptionCrudController extends AdminCrudController {

    public function setup() {
        $this->crud->setModel('App\Models\ContactOption');
        $this->crud->setRoute('admin/contact-options');
        $this->crud->setEntityNameStrings('contact option', 'contact options');
        $this->crud->orderBy('slug', 'desc');

        $this->addStringCrudColumn('name_en', 'Name (English)');
        $this->addStringCrudColumn('name_th', 'Name (Thai)');
        $this->addStringCrudColumn('email', 'Email');
        $this->addBooleanCrudColumn('active', 'Active?');
        $this->addBooleanCrudColumn('published', 'Published?');

        $this->addStringCrudField('name_en', 'Name (English)');
        $this->addStringCrudField('name_th', 'Name (Thai)');
        $this->addBodyEnCrudField();
        $this->addBodyThCrudField();
        $this->addStringCrudField('email', 'Email');
        $this->addActiveCrudField();
        $this->addPublishedCrudField();
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
