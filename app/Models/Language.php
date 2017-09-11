<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Language extends Model
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * The attributes that should not be revisioned.
     *
     * @var array
     */
    protected $dontKeepRevisionOf = [
        'slug', 'deleted_at',
    ];

    /**
     * The friendly name for revisions.
     *
     * @return string
     */
    public function identifiableName()
    {
        return $this->title_en;
    }

}
