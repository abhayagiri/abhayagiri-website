<?php

// This file is based on:
// https://github.com/Laravel-Backpack/Settings/blob/master/src/app/Http/Controllers/SettingCrudController.php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION
use App\Http\Requests\SettingCrudRequest as StoreRequest;
use App\Http\Requests\SettingCrudRequest as UpdateRequest;

class SettingCrudController extends CrudController
{
    public function __construct()
    {
        parent::__construct();

        $this->crud->setModel('App\Models\Setting');
        $this->crud->setRoute('admin/settings');
        $this->crud->setEntityNameStrings('setting', 'settings');
        $this->crud->orderBy('name');
        $this->crud->denyAccess(['create', 'delete']);
        $this->crud->setColumns([
            [
                'name'  => 'name',
                'label' => 'Name',
            ],
            [
                'name'  => 'value',
                'label' => 'Value',
            ],
            [
                'name'  => 'description',
                'label' => 'Description',
            ],
        ]);
        $this->crud->addField([
            'name'       => 'name',
            'label'      => 'Name',
            'type'       => 'text',
            'attributes' => [
                'disabled' => 'disabled',
            ],
        ]);
    }

    /**
     * Display all rows in the database for this entity.
     * This overwrites the default CrudController behaviour:
     * - instead of showing all entries, only show the "active" ones.
     *
     * @return Response
     */
    public function index()
    {
        $this->crud->addClause('where', 'active', 1);

        return parent::index();
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');

        $this->data['entry'] = $this->crud->getEntry($id);
        $this->crud->addField((array) json_decode($this->data['entry']->field)); // <---- this is where it's different
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud();
    }
}
