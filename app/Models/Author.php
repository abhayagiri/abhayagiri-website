<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Author extends Model
{
    use CamelCaseTrait;
    use ImageUrlTrait;
    use CrudTrait;
    use IconTrait;

    protected $fillable = ['slug', 'title_en', 'title_th', 'check_translation',
        'image_path', 'created_at', 'updated_at'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('titleOrder', function (Builder $builder) {
            $builder->orderBy('title_en');
        });
    }

    /**
     * Automatically set slug.
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title_en'] = $value;
        $this->attributes['slug'] = str_slug($value);
    }

    public function toArray()
    {
        $array = $this->camelizeArray(parent::toArray());
        $array = $this->addImageUrl($array);
        return $array;
    }

}
