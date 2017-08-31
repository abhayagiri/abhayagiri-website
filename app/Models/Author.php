<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Author extends Model
{
    use CamelCaseTrait;
    use ImageUrlTrait;
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
        $array = $this->camelizeArray(parent::toArray());
        $array = $this->addImageUrl($array);
        $array['slug'] = $array['urlTitle'];
        unset($array['urlTitle']);
        $array['titleEn'] = $array['title'];
        unset($array['title']);
        return $array;
    }

}
