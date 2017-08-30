<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Subject extends Model
{
    use CamelCaseTrait;
	use CrudTrait;
    use IconTrait;

	protected $fillable = ['slug', 'group_id', 'title_en', 'title_th',
        'description_en', 'description_th', 'check_translation', 'image_path',
        'rank', 'created_at', 'updated_at'];

    /**
     * Get parent subject group.
     */
    public function group()
    {
        return $this->belongsTo('App\Models\SubjectGroup');
    }

    /**
     * Get the related tags.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }

}
