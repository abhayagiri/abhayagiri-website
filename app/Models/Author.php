<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Author extends Model
{

    use CamelCaseTrait {
        toArray as camelCaseToArray;
    }
    use CrudTrait;
    use IconTrait;

    protected $fillable = ['url_title', 'title', 'title_th', 'check_translation', 'image_path', 'created_at', 'updated_at'];

    /**
     * Automatically set slug.
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['url_title'] = str_slug($value);
    }

    public function toArray()
    {
        $array = $this->camelCaseToArray();
        $array['slug'] = $array['urlTitle'];
        unset($array['urlTitle']);
        $array['titleEn'] = $array['title'];
        unset($array['title']);
        if ($array['imagePath']) {
            $array['imageUrl'] = '/media/' . $array['imagePath'];
        } else {
            // TEMP set a default image path if none is defined.
            $array['imageUrl'] = '/media/images/speakers/speakers_abhayagiri_sangha.jpg';
        }
        return $array;
    }

}
