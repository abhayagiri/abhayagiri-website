<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

use App\Http\Requests\BookCrudRequest as StoreRequest;
use App\Http\Requests\BookCrudRequest as UpdateRequest;

class BookCrudController extends CrudController {

    use CommonCrudTrait;

    public function setup()
    {
        $this->crud->setModel('App\Models\Book');
        $this->crud->setRoute('admin/books');
        $this->crud->orderBy('posted_at', 'desc');
        $this->crud->setEntityNameStrings('book', 'books');

        $this->addTrashedCrudFilter();

        $this->addImageCrudColumn();
        $this->addAuthorCrudColumn();
        $this->addTitleCrudColumn('title', 'Title');
        $this->crud->addColumn([
            'label' => 'Availability',
            'type' => 'model_function',
            'function_name' => 'getAvailabilityHtml',
        ]);
        $this->addCheckTranslationCrudColumn();
        $this->addDateTimeCrudColumn('local_posted_at', 'Posted');

        $this->addLanguageCrudField();
        $this->addAuthorCrudField();
        $this->addAuthorCrudField('author2_id', '2nd Author (optional)');
        $this->addTitleCrudField('title', 'Title');
        $this->addTitleCrudField('subtitle', 'Subtitle');
        $this->addTitleCrudField('alt_title_en', 'Title in English (if necessary)');
        $this->addTitleCrudField('alt_title_th', 'Title in Thai (if necessary)');
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
        $this->addDateCrudField('published_on', 'Published');
        $this->addDateTimeCrudField('local_posted_at', 'Posted');
        $this->addDraftCrudField();
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
