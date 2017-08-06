<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Tag extends Model
{

    use CamelCaseTrait;
    use CrudTrait;

    protected $fillable = ['slug', 'genre_id', 'title_en', 'title_th', 'check_translation', 'image_path', 'rank', 'created_at', 'updated_at'];

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

    public function getIconHtml()
    {
        if ($this->image_path) {
            $path = '/media/' . $this->image_path;
            return '<img width="50" src="' . \e($path) . '">';
        } else {
            return '';
        }
    }

}
