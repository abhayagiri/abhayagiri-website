<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class SubjectGroup extends Model
{
    use CamelCaseTrait;
    use ImageUrlTrait;
    use CrudTrait;
    use IconTrait;
    use DescriptionTrait;

    protected $fillable = ['slug', 'title_en', 'title_th',
        'description_en', 'description_th', 'check_translation', 'image_path',
        'rank', 'created_at', 'updated_at'];

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
     * Get the subjects.
     */
    public function subjects()
    {
        return $this->hasMany('App\Models\Subject', 'group_id');
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array = $this->convertDescriptionsToHtml($array);
        $array = $this->camelizeArray($array);
        $array = $this->addImageUrl($array);
        return $array;
    }
}
