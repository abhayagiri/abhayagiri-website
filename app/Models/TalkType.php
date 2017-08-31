<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class TalkType extends Model
{
    use CamelCaseTrait;
    use ImageUrlTrait;
    use CrudTrait;
    use IconTrait;

    protected $fillable = ['slug', 'title_en', 'title_th',
        'description_en', 'description_th', 'check_translation', 'image_path',
        'rank', 'created_at', 'updated_at'];

    /**
     * Get the talks.
     */
    public function talks()
    {
        return $this->hasMany('App\Models\Talk');
    }

    public function toArray()
    {
        $array = $this->camelizeArray(parent::toArray());
        $array = $this->addImageUrl($array);
        return $array;
    }
}
