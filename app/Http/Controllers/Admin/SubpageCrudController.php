<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubpageRequest;

class SubpageCrudController extends AdminCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\RevisionsOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Subpage');
        $this->crud->setRoute('admin/subpages');
        $this->crud->setEntityNameStrings('subpage', 'subpages');
    }

    protected function setupListOperation()
    {
        $this->crud->setDefaultPageLength(100);
        $this->crud->orderBy('draft', 'desc')->orderBy('page')
                   ->orderBy('rank')->orderBy('subpath');

        $this->addCheckTranslationCrudFilter();
        $this->addTrashedCrudFilter();

        $this->addStringCrudColumn('page', 'Page');
        $this->addStringCrudColumn('subpath', 'Subpath');
        $this->addTitleEnCrudColumn();
        $this->addRankCrudColumn();
        $this->addDraftCrudColumn();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(SubpageRequest::class);

        $this->crud->addField([
            'name' => 'page',
            'label' => 'Page',
            'type' => 'select_from_array',
            'options' => $this->getPageCrudFieldOptions(),
            'allows_null' => true,
        ]);
        $this->addStringCrudField('subpath', 'Subpath');
        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->addBodyEnCrudField();
        $this->addBodyThCrudField();
        $this->addCheckTranslationCrudField();
        $this->addRankCrudField();
        $this->addDraftCrudField();
        $this->addLocalPostedAtCrudField();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function getPageCrudFieldOptions()
    {
        return [
            'about' => 'About',
            'community' => 'Community',
            'support' => 'Support',
            'visiting' => 'Visiting',
        ];
    }
}
