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
        'author', 'url_title', 'date', 'body', 'description_th',
        'language', 'mp3', 'youtube_id', 'status', 'recording_date'];

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
