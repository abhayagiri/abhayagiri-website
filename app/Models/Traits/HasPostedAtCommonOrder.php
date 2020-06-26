<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait HasPostedAtCommonOrder
{
    /**
     * Return the model in common ordering.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCommonOrder(Builder $query): Builder
    {
        return (
            $this->scopePostedAtOrder($query)->orderBy(
                $this->getQualifiedKeyName(), 'desc'
            )
        );
    }
}
