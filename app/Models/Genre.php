<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{

    /**
     * Get the tags for the genre.
     */
    public function tags()
    {
        return $this->hasMany('App\Tag');
    }

}
