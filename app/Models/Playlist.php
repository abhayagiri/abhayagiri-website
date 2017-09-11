<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Venturecraft\Revisionable\RevisionableTrait;

use App\Models\Talk;

class Playlist extends Model
{
    use CamelCaseTrait;
    use ImageUrlTrait;
    use CrudTrait;
    use IconTrait;
    use DescriptionTrait;
    use RevisionableTrait;
    use SoftDeletes;

    protected $fillable = ['slug', 'title_en', 'title_th',
        'description_en', 'description_th', 'check_translation', 'image_path',
        'rank', 'published_at', 'status', 'created_at', 'updated_at'];

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
     * Get the talks.
     */
    public function talks()
    {
        return $this->belongsToMany('App\Models\Talk');
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
