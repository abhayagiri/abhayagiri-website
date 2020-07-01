<?php

namespace App\Models\Setting;

use App\Models\Setting;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Parental\HasParent;

class PlaylistGroupSetting extends Setting
{
    use HasParent;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'playlist_group', 'value'];

    /**
     * Callback for adding admin CRUD fields.
     *
     * @param  \Backpack\CRUD\app\Http\Controllers\CrudController  $controller
     *
     * @return void
     */
    public function addCrudFields(CrudController $controller): void
    {
        $controller->crud->addField([
            'name' => 'value',
            'label' => 'Playlist Group',
            'type' => 'select',
            'entity' => 'playlist_group',
            'attribute' => 'title_en',
            'model' => 'App\Models\PlaylistGroup',
        ]);
    }

    /**
     * Return HTML for the value column in the admin CRUD list.
     *
     * @return string
     */
    public function getCrudListHtml(): string
    {
        return $this->limitCrudListHtml($this->playlist_group->title_en ?? '');
    }

    /**
     * Relationship for playlist_group accessor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function playlist_group(): BelongsTo
    {
        return $this->belongsTo('App\Models\PlaylistGroup', 'value');
    }
}
