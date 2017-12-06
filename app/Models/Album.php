<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;

class Album extends Model
{
    use Traits\AutoSlugTrait;
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

    /**********
     * Scopes *
     **********/

    public function scopeByRank($query)
    {
        return $query
            ->orderBy($this->getTable() . '.rank', 'asc')
            ->orderBy($this->getTable() . '.created_at', 'desc');
    }

    /*****************
     * Relationships *
     *****************/

    public function photos()
    {
        return $this->belongsToMany('App\Models\Photo')
            ->withPivot('rank')
            ->withTimestamps();
    }

    public function thumbnail()
    {
        return $this->belongsTo('App\Models\Photo');
    }

    public static function getMacroHtml($id, $caption, $lng)
    {
        \Illuminate\Support\Facades\Log::debug($caption);
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
}
