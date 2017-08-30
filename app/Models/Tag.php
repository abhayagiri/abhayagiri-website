<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Tag extends Model
{

    use CamelCaseTrait;
    use CrudTrait;
    use IconTrait;

    protected $fillable = ['slug', 'title_en', 'title_th',
        'check_translation', 'created_at', 'updated_at'];

    /**
     * Get the related subjects.
     */
    public function subjects()
    {
        return $this->belongsToMany('App\Models\Subject');
    }

    /**
     * Get the talks for the tag.
     */
    public function talks()
    {
        return $this->belongsToMany('App\Models\Talk');
    }

}
