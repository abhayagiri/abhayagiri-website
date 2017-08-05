<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Tag extends Model
{

    use CrudTrait;

    protected $fillable = ['genre_id', 'title_en', 'title_th', 'check_translation', 'image_path', 'rank', 'created_at', 'updated_at'];

    /**
     * Get parent genre.
     */
    public function genre()
    {
        return $this->belongsTo('App\Models\Genre');
    }

    /**
     * Get the talks for the tag.
     */
    public function talks()
    {
        return $this->belongsToMany('App\Models\Talk');
    }

}
