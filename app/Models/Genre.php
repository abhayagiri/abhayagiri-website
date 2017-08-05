<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Genre extends Model
{

	use CrudTrait;

	protected $fillable = ['title_en', 'title_th', 'check_translation', 'image_path', 'rank', 'created_at', 'updated_at'];

    /**
     * Get the tags for the genre.
     */
    public function tags()
    {
        return $this->hasMany('App\Models\Tag');
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
