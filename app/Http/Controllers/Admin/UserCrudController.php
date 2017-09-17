<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserCrudRequest as StoreRequest;
use App\Http\Requests\UserCrudRequest as UpdateRequest;

class UserCrudController extends AdminCrudController {

    public function setup()
    {
        $this->crud->setModel('App\User');
        $this->crud->setRoute('admin/users');
        $this->crud->orderBy('name');
        $this->crud->setEntityNameStrings('user', 'users');
        $this->crud->allowAccess('revisions');
        $this->crud->with('revisionHistory');

        $this->addTrashedCrudFilter();

        $this->crud->addColumns([
            [
                'name' => 'name',
                'label' => 'Name',
            ],
            [
                'name' => 'email',
                'label' => 'Email',
            ],
            [
                'name' => 'is_super_admin',
                'label' => 'Super Administrator?',
                'type' => 'boolean',
            ],
        ]);

        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => 'Name',
            ],
            [
                'name' => 'email',
                'label' => 'Email',
            ],
            [
                'name' => 'is_super_admin',
                'label' => 'Super Administrator?',
                'type' => 'checkbox',
            ],
        ]);

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
