<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;

class Album extends Model
{
    use Traits\AutoSlugTrait;
    use Traits\HasAssociated;
    use Traits\HasPath;
    use Traits\IsSearchable;
    use Traits\LocalizedAttributes;
    use Traits\MarkdownHtmlTrait;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attribute or method that derives the slug.
     *
     * @var string
     */
    protected $slugFrom = 'title_en';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['description_html_en', 'description_html_th'];

    /**
     * The maximum number of records that should be indexed in testing
     * environments. A negative number means all records.
     *
     * @var int
     */
    protected $testingSearchMaxRecords = 10;

    /*
     * Scopes *
     */

    /**
     * Return the Albums in common ordering.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCommonOrder(Builder $query): Builder
    {
        return $query
            ->orderBy($this->getTable() . '.rank', 'asc')
            ->orderBy($this->getTable() . '.' . $this->getKeyName(), 'desc');
    }

    /**
     * Return a scope for public albums.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query;
    }

    /*
     * Relationships *
     */

    public function photos()
    {
        return $this->belongsToMany('App\Models\Photo')
            ->withPivot('rank')
            ->orderBy('album_photo.rank', 'asc')
            ->withTimestamps();
    }

    public function thumbnail()
    {
        return $this->belongsTo('App\Models\Photo');
    }

    public static function getMacroHtml($id, $caption, $lng)
    {
        $album = self::find((int) $id);
        if ($album) {
            return View::make('gallery/embed-album', [
                'album' => $album,
                'caption' => $caption,
                'lng' => $lng,
            ])->render();
        } else {
            return '<p>No such album: ' . e($id) . '</p>';
        }
    }

    /**
     * Return whether or not this is publicly visible.
     *
     * @return bool
     */
    public function isPublic(): bool
    {
        return true;
    }


    /**
     * Return the Aloglia indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        $result = $this->getBaseSearchableArray('description');
        foreach (['en', 'th'] as $lng) {
            $captions = $this->photos->pluck('caption_' . $lng)->filter();
            if ($captions->count()) {
                $result['text']['body_' . $lng] .= "\n\n- " .
                    $captions->join("\n- ");
            }
        }
        return $result;
    }

    /**
     * Return the name for the show route.
     *
     * @return string
     */
    protected function getRouteName(): string
    {
        return 'gallery.show';
    }
}
