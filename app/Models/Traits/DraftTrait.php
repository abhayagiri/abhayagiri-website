<?php

namespace App\Models\Traits;

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
        return $query->where($this->getTable() . '.draft', false);
    }

    /**
     * Scope a query to include only draft entries.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where($this->getTable() . 'draft', true);
    }
}
