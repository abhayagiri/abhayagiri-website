<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SettingRequest;

class SettingCrudController extends AdminCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as parentUpdate;
    }
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    //use \Backpack\ReviseOperation\ReviseOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Setting');
        $this->crud->setRoute('admin/settings');
        $this->crud->setEntityNameStrings('setting', 'settings');
    }

    protected function setupListOperation()
    {
        $this->crud->orderBy('key', 'asc');
        $this->crud->setDefaultPageLength(100);

        $this->crud->setColumns([
            [
                'name'  => 'key',
                'label' => 'Key',
            ],
            [
                'name'  => 'value',
                'label' => 'Value',
                'type'  => 'model_function',
                'function_name' => 'getCrudListHtml',
                'limit' => 1000,
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(SettingRequest::class);

        $this->crud->addField([
            'name'       => 'key',
            'label'      => 'Key',
            'type'       => 'text',
            'attributes' => [
                'disabled' => 'disabled',
            ],
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
        $this->crud->getCurrentEntry()->addCrudFields($this);
    }
}
