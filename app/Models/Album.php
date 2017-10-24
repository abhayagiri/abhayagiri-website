<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use Traits\AutoSlugTrait;

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
}
