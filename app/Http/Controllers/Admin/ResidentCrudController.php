<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ResidentRequest;
use App\Http\Controllers\Admin\Operations\RestoreOperation;

class ResidentCrudController extends AdminCrudController
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
        $this->crud->setModel('App\Models\Resident');
        $this->crud->setRoute('admin/residents');
        $this->crud->setEntityNameStrings('resident', 'residents');
    }

    protected function getStatusCrudFieldOptions()
    {
        return [
            'current' => 'Current',
            'traveling' => 'Traveling',
            'former' => 'Former (unlisted)',
        ];
    }

    protected function setupListOperation()
    {
        $this->crud->setDefaultPageLength(100);
        $this->crud->orderBy('rank')->orderBy('title_en');

        $this->addCheckTranslationCrudFilter();
        $this->addTrashedCrudFilter();

        $this->addImageCrudColumn();
        $this->addTitleEnCrudColumn();
        $this->addTitleThCrudColumn();
        $this->addRankCrudColumn();
        $this->addCheckTranslationCrudColumn();
        $this->crud->addColumn([
            'name' => 'status',
            'label' => 'Status',
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ResidentRequest::class);

        $this->crud->addField([
            'name' => 'slug',
            'label' => 'Slug',
            'hint' => 'Simple name for URLs (use lowercase; no title, spaces or diacritics).'
        ]);
        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->addDescriptionEnCrudField();
        $this->addDescriptionThCrudField();
        $this->addCheckTranslationCrudField();
        $this->addImageCrudField();
        $this->addRankCrudField();
        $this->crud->addField([
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select_from_array',
            'options' => $this->getStatusCrudFieldOptions(),
            'allows_null' => true,
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
