<?php

namespace App\Models;

use App\Util;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class Author extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use Traits\AutoSlugTrait;
    use Traits\ImagePathTrait;
    use Traits\MediaPathTrait;
    use Traits\LocalDateTimeTrait;
    use Traits\ImageCrudColumnTrait;
    use Traits\RevisionableTrait;

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
        'visiting' => 'boolean',
        'check_translation' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['image_url', 'talks_path'];

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
     * Override to store the creation as a revision
     *
     * @var bool
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

    /*
     * Scopes *
     */

    public function scopeWithTalkCount($query)
    {
        $query->select('authors.*', DB::raw('COUNT(talks.id) AS talk_count'))
            ->join('talks', 'talks.author_id', '=', 'authors.id', 'LEFT OUTER')
            ->groupBy('authors.id');
    }

    public function scopeByPopularity($query, $minTalks = 100, $minDays = 90)
    {
        $minDate = (new Carbon('' . intval($minDays) . ' days ago'))->toDateString();
        $query->select(
            'authors.*',
            DB::raw('(COUNT(`talks`.`id`) > ' . intval($minTalks) . ' OR ' .
                         'MAX(`talks`.`recorded_on`) > \'' . $minDate . '\') AS `popular`')
        )
        ->join('talks', 'talks.author_id', '=', 'authors.id', 'LEFT OUTER')
        ->groupBy('authors.id')
        ->orderBy('popular', 'desc')
        ->orderBy('title_en');
    }

    /*
     * Relationships *
     */

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

    /*
     * Accessors and Mutators *
     */

    public function getTalksPathAttribute()
    {
        return '/talks/teachers/' . $this->getKey() . '-' .
            $this->getAttribute('slug');
    }

    /*
     * Other *
     */

    /**
     * Return the canonical "Abhayagiri Sangha" author.
     *
     * TODO: This is brittle.
     *
     * @return Author
     *
     * @throws RuntimeException
     */
    public static function sangha() : Author
    {
        $sangha = Author::where('title_en', 'Abhayagiri Sangha')->first();
        if ($sangha) {
            return $sangha;
        } else {
            throw new RuntimeException('Cannot find Abhayagiri Sangha author');
        }
    }

    /**
     * Return authors whose title_en monk-name-equals $name.
     *
     * @see App\Util::isEqualMonkName()
     *
     * @param  string  $name
     *
     * @return \Illuminate\Support\Collection
     */
    public static function searchByMonkName(string $name) : Collection
    {
        $candidates = static::where('title_en', $name)->get();
        if ($candidates->count() == 0) {
            return static::all()->filter(function ($author) use ($name) {
                return Util::isEqualMonkName($name, $author->title_en);
            });
        } else {
            return $candidates;
        }
    }
}
