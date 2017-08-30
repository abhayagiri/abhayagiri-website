<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class SubjectGroup extends Model
{
    use CamelCaseTrait;
    use CrudTrait;
    use IconTrait;

    protected $fillable = ['slug', 'title_en', 'title_th',
        'description_en', 'description_th', 'check_translation', 'image_path',
        'rank', 'created_at', 'updated_at'];

    /**
     * Get the subjects.
     */
    public function subjects()
    {
        return $this->hasMany('App\Models\Subject');
    }

}
