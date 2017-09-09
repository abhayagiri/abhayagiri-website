<?php

namespace App\Models;

use Carbon\Carbon;

trait DraftTrait
{
    /**
     * Scope a query to exclude draft entries.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query)
    {
        return $query->where('draft', false);
    }

    /**
     * Scope a query to include only draft entries.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('draft', true);
    }
}
