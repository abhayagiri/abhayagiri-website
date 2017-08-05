<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    /**
     * Get parent genre.
     */
    public function genre()
    {
        return $this->belongsTo('App\Genre');
    }

    /**
     * Get the talks for the tag.
     */
    public function talks()
    {
        return $this->belongsToMany('App\Talk');
    }

}
