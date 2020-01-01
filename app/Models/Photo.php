<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
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
    protected $slugFrom = 'caption_en';

    /*
     * Relationships *
     */

    public function albums()
    {
        return $this->belongsToMany('App\Models\Album')
            ->withPivot('rank')
            ->withTimestamps();
    }
}
