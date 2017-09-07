<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Backpack\CRUD\CrudTrait;
use Weevers\Path\Path;

use App\Models\Subject;

class Talk extends Model
{
    use CrudTrait;

    public $timestamps = false;

    protected $fillable = ['type_id', 'title', 'title_th',
        'author_id', 'url_title', 'date', 'body', 'description_th',
        'language', 'mp3', 'youtube_id', 'language', 'hide_from_latest', 'status', 'recording_date'];

    /**
     * Automatically set slug.
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['url_title'] = str_slug($value);
    }

    public function getSubjects()
    {
        $subjectIds = DB::table('tag_talk')
            ->join('subject_tag', 'tag_talk.tag_id', '=', 'subject_tag.tag_id')
            ->where('tag_talk.talk_id', '=', $this->id)
            ->pluck('subject_tag.subject_id');
        return Subject::whereIn('id', $subjectIds);
    }

    // TODO rethink
    public function getPath($lng = 'en')
    {
        $path = $lng === 'th' ? '/new/th/talks/' : '/new/talks/';
        return $path . $this->id . '-' . str_slug($this->title);
    }

    // TODO rethink
    public function getLocalizedDate()
    {
        $date = new \Carbon\Carbon($this->date, 'America/Los_Angeles');
        return $date->toFormattedDateString();
    }

    // TODO yuck
    public function getSummaryHtml()
    {
        $func = new \App\Legacy\Func();
        return $func->abridge($this->body, 200);
    }

    /**
     * Get the author.
     */
    public function author()
    {
        return $this->belongsTo('App\Models\Author');
    }

    /**
     * Get the playlists.
     */
    public function playlists()
    {
        return $this->belongsToMany('App\Models\Playlist');
    }

    /**
     * Get the talk type.
     */
    public function type()
    {
        return $this->belongsTo('App\Models\TalkType');
    }

    /**
     * Get the tags.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }
}
