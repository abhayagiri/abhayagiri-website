<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Tag extends Model
{
    use CamelCaseTrait;
    use ImageUrlTrait;
    use CrudTrait;
    use IconTrait;
    use RevisionableTrait;
    // use SoftDeletes;

    protected $fillable = ['slug', 'title_en', 'title_th',
        'check_translation', 'created_at', 'updated_at'];

    /**
     * The attributes that should not be revisioned.
     *
     * @var array
     */
    protected $dontKeepRevisionOf = [
        'slug', 'deleted_at',
    ];

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
     * The friendly name for revisions.
     *
     * @return string
     */
    public function identifiableName()
    {
        return $this->title_en;
    }

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

    public function toArray()
    {
        $array = $this->camelizeArray(parent::toArray());
        $array = $this->addImageUrl($array);
        return $array;
    }
}
