<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;

trait CommonCrudTrait
{

    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     *
     * @return redirect
     */
    public function restore(Request $request, $id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->model->withTrashed()->find($id)->restore();
        return back();
    }

    public function addAuthorCrudColumn($column = 'author_id', $label = 'Author')
    {
        $this->crud->addColumn([
            'name' => $column,
            'label' => $label,
            'type' => 'select',
            'entity' => 'author',
            'attribute' => 'title_en',
            'model' => 'App\Models\Author',
        ]);
    }

    public function addAuthorCrudField($column = 'author_id', $label = 'Author')
    {
        $this->crud->addField([
            'name' => $column,
            'label' => $label,
            'type' => 'select',
            'entity' => 'author',
            'attribute' => 'title_en',
            'model' => 'App\Models\Author',
        ]);
    }

    public function addCheckTranslationCrudColumn()
    {
        $this->crud->addColumn([
            'name' => 'check_translation',
            'label' => 'Check Translation?',
            'type' => 'boolean',
        ]);
    }

    public function addCheckTranslationCrudField()
    {
        $this->crud->addField([
            'name' => 'check_translation',
            'label' => 'Check Translation',
            'type' => 'checkbox',
            'default' => '1',
            'hint' => 'Check this box if this entry needs Thai translation assistance.',
        ]);
    }

    public function addCheckTranslationCrudFilter()
    {
        $this->crud->addFilter([
          'type' => 'simple',
          'name' => 'check_translation',
          'label'=> 'Check Translation?'
        ],
        false,
        function() {
            $this->crud->addClause('where', 'check_translation', '=', true);
        });
    }

    public function addDateCrudField($column, $label)
    {
        $this->crud->addField([
            'name' => $column,
            'label' => $label,
            'type' => 'date',
            'default' => Carbon::now()->toDateString(),
        ]);
    }

    public function addDateTimeCrudColumn($column, $label)
    {
        $this->crud->addColumn([
            'name' => $column,
            'label' => $label,
            'type' => 'datetime',
        ]);
    }

    public function addDateTimeCrudField($column, $label, $default = null)
    {
        $this->crud->addField([
            'name' => $column,
            'label' => $label,
            'type' => 'datetime',
            'default' => $default,
        ]);
    }

    public function addDescriptionCrudField($column, $label)
    {
        $this->crud->addField([
            'name' => $column,
            'label' => $label,
            'type' => 'simplemde',
        ]);
    }

    public function addDescriptionEnCrudField()
    {
        $this->addDescriptionCrudField('description_en', 'Description (English)');
    }

    public function addDescriptionThCrudField()
    {
        $this->addDescriptionCrudField('description_th', 'Description (Thai)');
    }

    public function addDraftCrudField()
    {
        $this->crud->addField([
            'name' => 'draft',
            'label' => 'Draft',
            'type' => 'checkbox',
            'default' => '0',
            'hint' => 'Check this box to hide this entry from the public.',
        ]);
    }

    public function addImageCrudColumn($column = 'image_path', $label = 'Image')
    {
        $this->crud->addColumn([
            'name' => $column,
            'label' => $label,
            'type' => 'model_function',
            'function_name' => 'getImageCrudColumnHtml',
        ]);
    }

    public function addImageCrudField()
    {
        $this->addUploadCrudField('image_path', 'Image');
    }

    public function addLanguageCrudColumn($column = 'language_id', $label = 'Language')
    {
        $this->crud->addColumn([
            'name' => $column,
            'label' => $label,
            'type' => 'select',
            'entity' => 'language',
            'attribute' => 'title_en',
            'model' => 'App\Models\Language',
        ]);
    }

    public function addLanguageCrudField($column = 'language_id', $label = 'Language')
    {
        $this->crud->addField([
            'name' => $column,
            'label' => $label,
            'type' => 'select',
            'entity' => 'language',
            'attribute' => 'title_en',
            'model' => 'App\Models\Language',
            'default' => 1,
        ]);
    }

    public function addLocalPostedAtCrudColumn()
    {
        $this->addDateTimeCrudColumn('local_posted_at', 'Posted');
    }

    public function addLocalPostedAtCrudField()
    {
        // TODO should be local to user
        $timezone = 'America/Los_Angeles';
        $this->addDateTimeCrudField('local_posted_at', 'Posted',
            Carbon::now($timezone));
    }

    public function addRankCrudField()
    {
        $this->crud->addField([
            'name' => 'rank',
            'label' => 'Rank',
            'type' => 'number',
            'default' => '0',
            'hint' => 'Lower numbers are first, higher numbers are last.',
        ]);
    }

    public function addTitleCrudColumn($column, $label)
    {
        $this->crud->addColumn([
            'name' => $column,
            'label' => $label,
        ]);
    }

    public function addTitleEnCrudColumn()
    {
        $this->addTitleCrudColumn('title_en', 'Title (English)');
    }

    public function addTitleEnCrudField()
    {
        $this->addTitleCrudField('title_en', 'Title (English)');
    }

    public function addTitleCrudField($column, $label)
    {
        $this->crud->addField([
            'name' => $column,
            'label' => $label,
        ]);
    }

    public function addTitleThCrudColumn()
    {
        $this->addTitleCrudColumn('title_th', 'Title (Thai)');
    }

    public function addTitleThCrudField()
    {
        $this->addTitleCrudField('title_th', 'Title (Thai)');
    }

    public function addTrashedCrudFilter()
    {
        $this->crud->addFilter([
          'name' => 'trashed',
          'label'=> 'Trashed',
          'type' => 'simple',
        ],
        false,
        function() {
            $this->crud->addClause('onlyTrashed');
        });
    }

    public function addUploadCrudField($column, $label)
    {
        $this->crud->addField([
            'name' => $column,
            'label' => $label,
            'type' => 'browse',
        ]);
    }

}
