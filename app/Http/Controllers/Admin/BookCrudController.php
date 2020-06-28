<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookRequest;
use App\Http\Controllers\Admin\Operations\RestoreOperation;

class BookCrudController extends AdminCrudController
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
        $this->crud->setModel('App\Models\Book');
        $this->crud->setRoute('admin/books');
        $this->crud->setEntityNameStrings('book', 'books');
    }

    protected function setupListOperation()
    {
        $this->crud->orderBy('posted_at', 'desc');

        $this->addTrashedCrudFilter();

        $this->addImageCrudColumn();
        $this->addAuthorCrudColumn();
        $this->addTitleCrudColumn();
        $this->crud->addColumn([
            'label' => 'Availability',
            'type' => 'model_function',
            'function_name' => 'getAvailabilityCrudColumnHtml',
        ]);
        $this->addCheckTranslationCrudColumn();
        $this->addLocalPostedAtCrudColumn();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(BookRequest::class);

        $this->addLanguageCrudField();
        $this->addAuthorCrudField();
        $this->addAuthorCrudField('author2_id', '2nd Author (optional)');
        $this->addTitleCrudField();
        $this->addStringCrudField('subtitle', 'Subtitle');
        $this->addAltTitleEnCrudField();
        $this->addAltTitleThCrudField();
        $this->addDateCrudField('published_on', 'Published');
        $this->addDescriptionEnCrudField();
        $this->addDescriptionThCrudField();
        $this->addCheckTranslationCrudField();
        $this->addImageCrudField();
        $this->addUploadCrudField('pdf_path', 'PDF');
        $this->addUploadCrudField('epub_path', 'ePUB');
        $this->addUploadCrudField('mobi_path', 'Mobi');
        $this->crud->addField([
            'name' => 'weight',
            'label' => 'Weight',
        ]);
        $this->crud->addField([
            'name' => 'request',
            'label' => 'Copies Available?',
            'type' => 'checkbox',
        ]);
        $this->addDraftCrudField();
        $this->addLocalPostedAtCrudField();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
