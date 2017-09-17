<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookCrudRequest as StoreRequest;
use App\Http\Requests\BookCrudRequest as UpdateRequest;

class BookCrudController extends AdminCrudController {

    public function setup()
    {
        $this->crud->setModel('App\Models\Book');
        $this->crud->setRoute('admin/books');
        $this->crud->orderBy('posted_at', 'desc');
        $this->crud->setEntityNameStrings('book', 'books');
        $this->crud->allowAccess('revisions');
        $this->crud->with('revisionHistory');

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

    public function store(StoreRequest $request)
    {
        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }
}
