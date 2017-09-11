<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Backpack\CRUD\app\Http\Controllers\CrudController;

use App\Http\Requests\PlaylistCrudRequest as StoreRequest;
use App\Http\Requests\PlaylistCrudRequest as UpdateRequest;

class PlaylistCrudController extends CrudController {

    public function setup() {
        $this->crud->setModel('App\Models\Playlist');
        $this->crud->setRoute('admin/playlists');
        $this->crud->orderBy('rank')->orderBy('title_en');
        $this->crud->setEntityNameStrings('playlist', 'playlists');
        $this->crud->allowAccess('revisions');
        $this->crud->with('revisionHistory');

        $this->crud->addColumns([
            [
                'name' => 'image_path',
                'label' => 'Image',
                'type' => 'model_function',
                'function_name' => 'getIconHtml',
            ],
            [
                'name' => 'title_en',
                'label' => 'Title (English)',
            ],
            [
                'name' => 'title_th',
                'label' => 'Title (Thai)',
            ],
            [
                'name' => 'check_translation',
                'label' => 'Check Translation?',
                'type' => 'boolean',
            ],
        ]);
        $this->crud->addFields([
            [
                'name' => 'slug',
                'label' => 'Slug',
                'hint' => 'Short and unique name (for URLs)',
            ],
            [
                'name' => 'title_en',
                'label' => 'Title (English)',
            ],
            [
                'name' => 'title_th',
                'label' => 'Title (Thai)',
            ],
            [
                'name' => 'description_en',
                'label' => 'Description (English)',
                'type' => 'simplemde',
            ],
            [
                'name' => 'description_th',
                'label' => 'Description (Thai)',
                'type' => 'simplemde',
            ],
            [
                'name' => 'check_translation',
                'label' => 'Check Translation',
                'type' => 'checkbox',
                'default' => '1',
                'hint' => 'Check this box if this entry needs translation.',
            ],
            [
                'name' => 'image_path',
                'label' => 'Image',
                'type' => 'browse',
            ],
            [
                'name' => 'published_at',
                'label' => 'Publish Date',
                'type' => 'datetime',
                'default' => Carbon::now(),
            ],
            [
                'name' => 'rank',
                'label' => 'Rank',
                'type' => 'number',
                'default' => '0',
                'hint' => 'Lower numbers are first, higher numbers are last.',
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'select_from_array',
                'options' => ['open', 'closed'],
            ],
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
