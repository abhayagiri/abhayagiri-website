<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;

use App\Http\Requests\TalkCrudRequest as StoreRequest;
use App\Http\Requests\TalkCrudRequest as UpdateRequest;
use App\Models\Talk;
use App\Models\TalkType;
use App\Util;

class TalkCrudController extends AdminCrudController {

    public function setup() {
        $this->crud->setModel('App\Models\Talk');
        $this->crud->setRoute('admin/talks');
        $this->crud->setEntityNameStrings('talk', 'talks');
        $this->crud->setDefaultPageLength(100);
        $this->crud->orderBy('posted_at', 'desc');
        $this->crud->allowAccess('revisions');
        $this->crud->with('revisionHistory');

        $this->addCheckTranslationCrudFilter();
        $this->addTrashedCrudFilter();

        $this->addTitleEnCrudColumn();
        $this->addAuthorCrudColumn();
        $this->crud->addColumn([
            'name' => 'type_id',
            'label' => 'Type',
            'type' => 'select',
            'entity' => 'type',
            'attribute' => 'title_en',
            'model' => 'App\Models\TalkType',
        ]);
        $this->addLocalPostedAtCrudColumn();

        $this->addTitleEnCrudField();
        $this->addTitleThCrudField();
        $this->addAuthorCrudField();
        $this->addLanguageCrudField();
        $this->crud->addField([
            'name' => 'type_id',
            'label' => 'Type',
            'type' => 'select_from_array',
            'options' => $this->getTalkTypeCrudFieldOptions(),
            'allows_null' => true,
        ]);
        $this->addDateCrudField('recorded_on', 'Recorded');
        $this->addDescriptionEnCrudField();
        $this->addDescriptionThCrudField();
        $this->addCheckTranslationCrudField();
        $this->addImageCrudField();
        $this->addUploadCrudField('media_path', 'Media File (MP3, etc.)');
        $this->crud->addFields([
            [
                'name' => 'youtube_id',
                'label' => 'YouTube ID',
                'hint' => 'YouTube URLs are also okay',
            ],
            [
                'name' => 'tags',
                'label' => 'Tags',
                'type' => 'select2_multiple',
                'entity' => 'tags',
                'attribute' => 'title_en',
                'model' => 'App\Models\Tag',
                'pivot' => true,
            ],
            [
                'name' => 'playlists',
                'label' => 'Playlists',
                'type' => 'select2_multiple',
                'entity' => 'playlists',
                'attribute' => 'title_en',
                'model' => 'App\Models\Playlist',
                'pivot' => true,
            ],
            [
                'name' => 'hide_from_latest',
                'label' => 'Hide From Latest',
                'type' => 'checkbox',
                'default' => 0,
                'hint' => "Check this box if this talk shouldn't show up on the latest talks page (e.g. retreat talks).",
            ],
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

    public function xsearchAjax(Request $request)
    {
        $talks = Talk::select('talks.*')
            ->join('authors', 'authors.id', '=', 'talks.author_id')
            ->join('talk_types', 'talk_types.id', '=', 'talks.type_id');
        $recordsTotal = $talks->count();
        $searchText = trim((string) $request->input('search.value'));
        if ($searchText) {
            $likeQuery = '%' . Util::escapeLikeQueryText($searchText) . '%';
            $talks = $talks->where(function ($query) use ($searchText, $likeQuery) {
                $query->where('talks.id', '=', $searchText)
                      ->orWhere('talks.title_en', 'LIKE', $likeQuery)
                      ->orWhere('talks.title_th', 'LIKE', $likeQuery)
                      ->orWhere('talks.description_en', 'LIKE', $likeQuery)
                      ->orWhere('talks.description_th', 'LIKE', $likeQuery)
                      ->orWhere('authors.title_en', 'LIKE', $likeQuery)
                      ->orWhere('authors.title_th', 'LIKE', $likeQuery)
                      ->orWhere('talk_types.title_en', 'LIKE', $likeQuery)
                      ->orWhere('talk_types.title_th', 'LIKE', $likeQuery);
            });
        }
        if ($request->input('check_translation')) {
            $talks = $talks->where('talks.check_translation', true);
        }
        if ($request->input('trashed')) {
            $talks = $talks->onlyTrashed();
        }
        $recordsFiltered = $talks->count();

        $orderColumn = array_get([
            'talks.title_en',
            'authors.title_en',
            'talk_types.title_en',
            'talks.posted_at',
        ], (int) $request->input('order.0.column', 4), 'talks.posted_at');
        $orderDir = ($request->input('order.0.dir', 'desc') === 'desc') ? 'desc' : 'asc';

        $talks = $talks
            ->orderBy($orderColumn, $orderDir)
            ->offset($request->input('start', 0))
            ->limit($request->input('length', 25))
            ->with('type')
            ->with('author');

        return response()->json([
            'draw' => $request->input('draw'),
            'orderColumn' => $orderColumn,
            'orderDir' => $orderDir,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $talks->get()->map(function($talk) {
                return [
                    '<td>' . e($talk->title_en) . '</td>',
                    '<td>' . e($talk->author->title_en) . '</td>',
                    '<td>' . e($talk->type->title_en) . '</td>',
                    '<td>' . $talk->local_posted_at . '</td>',
                    '<a href="/admin/talks/' . $talk->id .
                        '/edit" class="btn btn-xs btn-default">' .
                        '<i class="fa fa-edit\"></i> Edit</a>',
                ];
            }),
        ]);
    }

    protected function getTalkTypeCrudFieldOptions()
    {
        $options = [];
        TalkType::orderBy('title_en')
                ->get()->each(function($talkType) use (&$options) {
            $options[$talkType->id] = $talkType->title_en;
        });
        return $options;
    }
}
