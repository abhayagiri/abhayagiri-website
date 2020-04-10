<?php

namespace App\Models\Traits;

trait FilterThai
{
    /**
     * Return a scope orderded by rank and posted_at.
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithThai($query)
    {
        return $query->whereNotNull('title_th')->whereNotNull('body_th');
    }
}
