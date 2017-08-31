<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Backpack\CRUD\CrudTrait;
use Weevers\Path\Path;

class Talk extends Model
{
    use CrudTrait;

    public $timestamps = false;

    protected $fillable = ['type_id', 'title', 'author', 'url_title', 'date', 'body',
        'language', 'mp3', 'youtube_id', 'status', 'recording_date'];

    /**
     * Automatically set slug.
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['url_title'] = str_slug($value);
    }

    /**
     * Get the talk type.
     */
    public function type()
    {
        return $this->belongsTo('App\Models\TalkType');
    }

    /**
     * Get the tags.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }
}
