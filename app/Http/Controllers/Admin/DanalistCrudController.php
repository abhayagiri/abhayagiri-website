<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DanalistCrudRequest as StoreRequest;
use App\Http\Requests\DanalistCrudRequest as UpdateRequest;

class DanalistCrudController extends AdminCrudController {

    public function setup() {
        $this->crud->setModel('App\Models\Danalist');
        $this->crud->setRoute('admin/danalist');
        $this->crud->setEntityNameStrings('dana wishlist', 'dana wishlist');
        $this->crud->orderBy('listed', 'desc');
        $this->crud->orderBy('title', 'asc');
        $this->crud->allowAccess('revisions');
        $this->crud->with('revisionHistory');

        $this->addTrashedCrudFilter();

        $this->addTitleCrudColumn();
        $this->crud->addColumn([
            'name' => 'link',
            'label' => 'Link',
            'type' => 'model_function',
            'function_name' => 'getLinkColumnHtml'
        ]);
        $this->crud->addColumn([
            'name' => 'listed',
            'label' => 'Listed',
            'type' => 'check',
        ]);

        $this->addTitleCrudField();
        $this->crud->addField([
            'name' => 'link',
            'label' => 'Link',
            'hint' => 'The full link, e.g., https://somewhere.com/123.',
        ]);
        $this->crud->addField([
            'name' => 'short_link',
            'label' => 'Short Link (optional)',
            'hint' => 'If you leave this blank, this will be automatically created for you.',
        ]);
        $this->addStringCrudField('summary_en', 'Summary (English)');
        $this->addStringCrudField('summary_th', 'Summary (Thai)');
        $this->addCheckTranslationCrudField();
        $this->crud->addField([
            'name' => 'listed',
            'label' => 'Listed',
            'type' => 'checkbox',
            'default' => '1',
            'hint' => 'Uncheck this to remove the item from the public listing.',
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
