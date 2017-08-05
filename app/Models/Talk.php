<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Talk extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'audio';

    /**
     * Get the tags for the talk.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

}
