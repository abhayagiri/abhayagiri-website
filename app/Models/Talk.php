<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

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
     * Get the tags for the talk.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }

}
