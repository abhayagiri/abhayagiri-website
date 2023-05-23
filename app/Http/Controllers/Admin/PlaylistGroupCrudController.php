<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PlaylistGroupRequest;
use App\Http\Controllers\Admin\Operations\RestoreOperation;

class PlaylistGroupCrudController extends AdminCrudController
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
        $this->crud->setModel('App\Models\PlaylistGroup');
        $this->crud->setRoute('admin/playlist-groups');
        $this->crud->setEntityNameStrings('playlist group', 'playlist groups');
    }

    protected function setupListOperation()
    {
        $this->crud->orderBy('rank')->orderBy('title_en');

        $this->addTrashedCrudFilter();

        $this->addImageCrudColumn();
        $this->addTitleEnCrudColumn();
        $this->addTitleThCrudColumn();
        $this->addCheckTranslationCrudColumn();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PlaylistGroupRequest::class);

        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->addDescriptionEnCrudField();
        $this->addDescriptionThCrudField();
        $this->addCheckTranslationCrudField();
        $this->addImageCrudField();
        $this->addRankCrudField();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
