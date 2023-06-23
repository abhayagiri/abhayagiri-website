<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DanalistRequest;
use App\Http\Controllers\Admin\Operations\RestoreOperation;

class DanalistCrudController extends AdminCrudController
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
        $this->crud->setModel('App\Models\Danalist');
        $this->crud->setRoute('admin/danalist');
        $this->crud->setEntityNameStrings('dana wishlist', 'dana wishlist');
    }

    protected function setupListOperation()
    {
        $this->crud->orderBy('listed', 'desc')->orderBy('title', 'asc');

        $this->addTrashedCrudFilter();

        $this->addTitleCrudColumn();
        $this->crud->addColumn([
            'name' => 'link',
            'label' => 'Link',
            'type' => 'model_function',
            'function_name' => 'getLinkColumnHtml',
            'escaped' => false,
        ]);
        $this->crud->addColumn([
            'name' => 'listed',
            'label' => 'Listed',
            'type' => 'check',
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(DanalistRequest::class);

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

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
