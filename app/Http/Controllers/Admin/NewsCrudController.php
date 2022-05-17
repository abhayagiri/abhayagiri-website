<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\NewsRequest;
use App\Http\Controllers\Admin\Operations\RestoreOperation;

class NewsCrudController extends AdminCrudController
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
        $this->crud->setModel('App\Models\News');
        $this->crud->setRoute('admin/news');
        $this->crud->setEntityNameStrings('news', 'news');
    }

    protected function setupListOperation()
    {
        if (!$this->request->has('order')) {
            $this->crud->addClause('postedAtOrder');
        }

        $this->addCheckTranslationCrudFilter();
        $this->addTrashedCrudFilter();

        $this->addTitleEnCrudColumn();
        $this->addRankCrudColumn();
        $this->addDraftCrudColumn();
        $this->addPostedAtCrudColumn();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(NewsRequest::class);

        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->addBodyEnCrudField();
        $this->addBodyThCrudField();
        $this->addCheckTranslationCrudField();
        $this->addImageCrudField();
        $this->addDraftCrudField();
        $this->addNullableRankCrudField();
        $this->addPostedAtCrudField();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
