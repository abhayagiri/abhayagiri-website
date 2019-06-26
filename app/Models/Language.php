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
        'deleted_at',
    ];

    /**
     * Override to store the creation as a revision
     *
     * @var boolean
     */
    protected $revisionCreationsEnabled = true;

    /**
     * The friendly name for revisions.
     *
     * @return string
     */
    public function identifiableName()
    {
        return $this->title_en;
    }

    /*****************
     * Relationships *
     *****************/

    public function books()
    {
        return $this->hasMany('App\Models\Book');
    }

    public function reflections()
    {
        return $this->hasMany('App\Models\Reflection');
    }

    public function talks()
    {
        return $this->hasMany('App\Models\Talk');
    }

    /*********
     * Other *
     *********/

    /**
     * Return the canonical model for 'English'.
     *
     * @return App\Models\Language
     */
    public static function english() : Language
    {
        return static::where('code', 'en')->firstOrFail();
    }

    /**
     * Return the canonical model for 'Thai'.
     *
     * @return App\Models\Language
     */
    public static function thai() : Language
    {
        return static::where('code', 'th')->firstOrFail();
    }
}
