<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Venturecraft\Revisionable\RevisionableTrait;

use App\Models\Talk;
use App\Scopes\TitleEnScope;

class Subject extends Model
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use Traits\AutoSlugTrait;
    use Traits\LocalDateTimeTrait;
    use Traits\ImageCrudColumnTrait;
    use Traits\ImagePathTrait;
    use Traits\MarkdownHtmlTrait;
    use Traits\MediaPathTrait;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'slug', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'check_translation' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['description_html_en', 'description_html_th',
        'image_url'];

    /**
     * The attributes that should not be revisioned.
     *
     * @var array
     */
    protected $dontKeepRevisionOf = [
        'slug', 'check_translation', 'deleted_at',
    ];

    /**
     * The attribute or method that derives the slug.
     *
     * @var string
     */
    protected $slugFrom = 'title_en';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new TitleEnScope);
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
     * Get the related talks IDs.
     *
     * @return array
     */
    public function getTalkIds()
    {
        $talkIds = DB::table('subject_tag')
            ->join('tag_talk', 'tag_talk.tag_id', '=', 'subject_tag.tag_id')
            ->where('subject_tag.subject_id', '=', $this->id)
            ->pluck('tag_talk.talk_id');
        return $talkIds;
    }

    /*****************
     * Relationships *
     *****************/

    public function group()
    {
        return $this->belongsTo('App\Models\SubjectGroup');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }
}
