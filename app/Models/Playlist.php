<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Backpack\CRUD\CrudTrait;

use App\Models\Talk;

class Playlist extends Model
{
    use CamelCaseTrait;
    use ImageUrlTrait;
    use CrudTrait;
    use IconTrait;

    protected $fillable = ['slug', 'title_en', 'title_th',
        'description_en', 'description_th', 'check_translation', 'image_path',
        'rank', 'published_at', 'status', 'created_at', 'updated_at'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('rankOrder', function (Builder $builder) {
            $builder
                ->orderBy('rank')
                ->orderBy('title_en');
        });
    }

    /**
     * Get the talks.
     */
    public function talks()
    {
        return $this->belongsToMany('App\Models\Talk');
    }

    public function toArray()
    {
        $array = $this->camelizeArray(parent::toArray());
        $array = $this->addImageUrl($array);
        return $array;
    }
}
