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

    protected $fillable = ['title', 'author', 'url_title', 'date', 'body', 'language', 'category', 'mp3', 'status', 'recording_date'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'audio';

    /**
     * Automatically set slug.
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['url_title'] = str_slug($value);
    }

    /**
     * Get the tags for the talk.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }

}
