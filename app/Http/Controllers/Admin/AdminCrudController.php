<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Requests\CrudRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Author;
use App\Models\Language;

abstract class AdminCrudController extends CrudController {

    /*
     * Derived classes should implement these methods.
     */

    // abstract public function setup();
    // abstract public function store(CrudRequest $request);
    // abstract public function update(CrudRequest $request);

    /*****************************
     * Common Controller Methods *
     *****************************/

    /**
     * Restore the specified resource from storage.
     *
     * @param Illuminate\Http\Request $request
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

    /**
     * As of 3.3, Laravel Backpack doesn't handle ordering very well.
     *
     * 1. Any orderBy's in setup() are not overriden.
     * 2. There's no ordering of non-database accessor columns.
     *
     * This fixes 1 by providing a 'orderLogic' parameter.
     * The second part is a little more complicated and will be addressed
     * later.
     *
     * @see https://github.com/if4lcon/laravel-clear-orders-by
     */
    public function search()
    {
        if ($this->request->input('order')) {
            $this->crud->query->clearOrdersBy();
            $column_number = $this->request->input('order')[0]['column'];
            if ($this->crud->details_row) {
                $column_number = $column_number - 1;
            }
            $column_direction = $this->request->input('order')[0]['dir'];
            $column = $this->crud->findColumnById($column_number);
            if (array_get($column, 'orderLogic')) {
                $column['orderLogic']($this->crud->query, $column, $column_direction);
            } else if ($column['tableColumn']) {
                $this->crud->orderBy($column['name'], $column_direction);
            }
        }
        return parent::search();
    }

    /*************************
     * General CRUD Creators *
     *************************/

    /**
     * Add a CRUD boolean column.
     *
     * @param string $attribute
     * @param string $label
     * @return void
     */
    public function addBooleanCrudColumn($attribute, $label)
    {
        $this->crud->addColumn([
            'name' => $attribute,
            'label' => $label,
            'type' => 'boolean',
        ]);
    }

    /**
     * Add a CRUD date column.
     *
     * @param string $attribute
     * @param string $label
     * @return void
     */
    public function addDateCrudColumn($attribute, $label)
    {
        $this->crud->addColumn([
            'name' => $attribute,
            'label' => $label,
            'type' => 'date',
        ]);
    }

    /**
     * Add a CRUD date field.
     *
     * @param string $attribute
     * @param string $label
     * @param mixed $default
     * @return void
     */
    public function addDateCrudField($attribute, $label, $default = null)
    {
        $this->crud->addField([
            'name' => $attribute,
            'label' => $label,
            'type' => 'date',
            'default' => $default,
        ]);
    }

    /**
     * Add a CRUD datetime column.
     *
     * @param string $attribute
     * @param string $label
     * @return void
     */
    public function addDateTimeCrudColumn($attribute, $label)
    {
        $this->crud->addColumn([
            'name' => $attribute,
            'label' => $label,
            'type' => 'datetime',
        ]);
    }

    /**
     * Add a CRUD datetime field.
     *
     * @param string $attribute
     * @param string $label
     * @param mixed $default
     * @return void
     */
    public function addDateTimeCrudField($attribute, $label, $default = null)
    {
        $this->crud->addField([
            'name' => $attribute,
            'label' => $label,
            'type' => 'datetime',
            'default' => $default,
        ]);
    }

    /**
     * Add a CRUD Markdown editor (for styled text).
     *
     * @param string $attribute
     * @param string $label
     * @return void
     */
    public function addMarkdownCrudField($attribute, $label)
    {
        $this->crud->addField([
            'name' => $attribute,
            'label' => $label,
            'type' => 'simplemde',
            'simplemdeAttributesRaw' => substr(json_encode([
                'promptURLs' => true,
                'spellChecker' => false,
                'shortcuts' => [
                    // These clash with Pali diacritics entry on Windows
                    // See http://fsnow.com/pali/keyboard/
                    // and https://github.com/sparksuite/simplemde-markdown-editor#keyboard-shortcuts
                    'toggleCodeBlock' => null,
                    'drawImage' => null,
                    'toggleOrderedList' => null,
                ],
            ]), 1, -1),
        ]);
    }

    /**
     * Add a CRUD string column.
     *
     * @param string $attribute
     * @param string $label
     * @return void
     */
    public function addStringCrudColumn($attribute, $label)
    {
        $this->crud->addColumn([
            'name' => $attribute,
            'label' => $label,
        ]);
    }

    /**
     * Add a CRUD string field.
     *
     * @param string $attribute
     * @param string $label
     * @return void
     */
    public function addStringCrudField($attribute, $label)
    {
        $this->crud->addField([
            'name' => $attribute,
            'label' => $label,
        ]);
    }

    /**
     * Add a CRud upload field.
     *
     * @param string $attribute
     * @param string $label
     * @return void
     */
    public function addUploadCrudField($column, $label)
    {
        $this->crud->addField([
            'name' => $column,
            'label' => $label,
            'type' => 'browse',
        ]);
    }

    /************************
     * Common CRUD Creators *
     ************************/

    public function addActiveCrudField()
    {
        $this->crud->addField([
            'name' => 'active',
            'label' => 'Active',
            'type' => 'checkbox',
            'default' => '0',
            'hint' => 'Uncheck this box if this entry should not include the contact form.',
        ]);
    }

    public function addPublishedCrudField()
    {
        $this->crud->addField([
            'name' => 'published',
            'label' => 'Published',
            'type' => 'checkbox',
            'default' => '0',
            'hint' => 'Uncheck this box if this entry should be hidden.',
        ]);
    }

    public function addAltTitleEnCrudField()
    {
        $this->addStringCrudField('alt_title_en', 'Title in English (if necessary)');
    }

    public function addAltTitleThCrudField()
    {
        $this->addStringCrudField('alt_title_th', 'Title in Thai (if necessary)');
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
            'type' => 'select_from_array',
            'options' => $this->getAuthorCrudFieldOptions(),
            'allows_null' => true,
        ]);
    }

    public function addBodyCrudField()
    {
        $this->addMarkdownCrudField('body', 'Body');
    }

    public function addBodyEnCrudField()
    {
        $this->addMarkdownCrudField('body_en', 'Body (English)');
    }

    public function addBodyThCrudField()
    {
        $this->addMarkdownCrudField('body_th', 'Body (Thai)');
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

    public function addDescriptionEnCrudField()
    {
        $this->addMarkdownCrudField('description_en', 'Description (English)');
    }

    public function addDescriptionThCrudField()
    {
        $this->addMarkdownCrudField('description_th', 'Description (Thai)');
    }

    public function addDraftCrudColumn()
    {
        $this->crud->addColumn([
            'name' => 'draft',
            'label' => 'Draft',
            'type' => 'check',
        ]);
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
            'type' => 'select_from_array',
            'options' => $this->getLanguageCrudFieldOptions(),
            'allows_null' => true,
        ]);
    }

    public function addLocalPostedAtCrudColumn()
    {
        $this->crud->addColumn([
            'name' => 'local_posted_at',
            'label' => 'Posted',
            'type' => 'datetime',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('posted_at', 'like', '%'.$searchTerm.'%');
            },
            'orderLogic' => function ($query, $column, $columnDirection) {
                $query->orderBy('posted_at', $columnDirection);
            },
        ]);
    }

    public function addLocalPostedAtCrudField()
    {
        // TODO should be local to user
        $timezone = 'America/Los_Angeles';
        $this->addDateTimeCrudField('local_posted_at', 'Posted',
            Carbon::now($timezone));
    }

    public function addRankCrudColumn()
    {
        $this->addStringCrudColumn('rank', 'Rank');
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

    public function addTitleCrudColumn()
    {
        $this->addStringCrudColumn('title', 'Title');
    }

    public function addTitleCrudField()
    {
        $this->addStringCrudField('title', 'Title');
    }

    public function addTitleEnCrudColumn()
    {
        $this->addStringCrudColumn('title_en', 'Title (English)');
    }

    public function addTitleEnCrudField()
    {
        $this->addStringCrudField('title_en', 'Title (English)');
    }

    public function addTitleThCrudColumn()
    {
        $this->addStringCrudColumn('title_th', 'Title (Thai)');
    }

    public function addTitleThCrudField()
    {
        $this->addStringCrudField('title_th', 'Title (Thai)');
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

    /*********
     * Other *
     *********/

    protected function getAuthorCrudFieldOptions()
    {
        $options = [];
        $authors = Author::byPopularity();
        $switch = false;
        $authors->get()->each(function($author) use (&$options, $switch) {
            if (!$author->popular && !$switch) {
                $options[''] = '-';
                $switch = true;
            }
            $options[$author->id] = $author->title_en;
        });
        return $options;
    }

    protected function getLanguageCrudFieldOptions()
    {
        $options = [];
        Language::orderBy('title_en')
                ->get()->each(function($language) use (&$options) {
            $options[$language->id] = $language->title_en;
        });
        return $options;
    }
}
