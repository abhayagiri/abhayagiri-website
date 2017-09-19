<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubpageCrudRequest as StoreRequest;
use App\Http\Requests\SubpageCrudRequest as UpdateRequest;

class SubpageCrudController extends AdminCrudController {

    public function setup() {
        $this->crud->setModel('App\Models\Subpage');
        $this->crud->setRoute('admin/subpages');
        $this->crud->setEntityNameStrings('subpage', 'subpages');
        $this->crud->setDefaultPageLength(100);
        $this->crud->orderBy('draft', 'desc');
        $this->crud->orderBy('page');
        $this->crud->orderBy('rank');
        $this->crud->orderBy('subpath');
        $this->crud->allowAccess('revisions');
        $this->crud->with('revisionHistory');

        $this->addCheckTranslationCrudFilter();
        $this->addTrashedCrudFilter();

        $this->addStringCrudColumn('page', 'Page');
        $this->addStringCrudColumn('subpath', 'Subpath');
        $this->addTitleEnCrudColumn();
        $this->addStringCrudColumn('rank', 'Rank');
        $this->addDraftCrudColumn();

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
        $this->addDraftCrudField();
        $this->addLocalPostedAtCrudField();
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
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
