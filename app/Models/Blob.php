<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Blob extends Model
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use Traits\MarkdownHtmlTrait;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'key', 'name', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'check_translation' => 'boolean',
    ];

    /**
     * The attributes that should not be revisioned.
     *
     * @var array
     */
    protected $dontKeepRevisionOf = [
        'key', 'name', 'check_translation', 'deleted_at',
    ];

    /*
     * Other
     */

    public static function getBlob($key, $options = [])
    {
        $model = static::where('key', $key)->first();
        if ($model) {
            $lng = array_get($options, 'lng') === 'th' ? 'th' : 'en';
            return $model->{'body_html_' . $lng};
        } else {
            return '';
        }
    }
}
