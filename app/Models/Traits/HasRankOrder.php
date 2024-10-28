<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HasRankOrder
{
    /**
     * The default rank if NULL.
     *
     * @var int
     */
    protected $defaultCoalesceRank = 100000;

    /**
     * Return a scope orderded by rank ascending.
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeRankOrder(Builder $query): Builder
    {
        return $query->orderByRaw(DB::raw($this->getCoalesceRank() . ' asc')->getValue(DB::connection()->getQueryGrammar()));
    }

    /**
     * Return the rank column coalesced with a default.
     *
     * @return string
     */
    protected function getCoalesceRank(): string
    {
        return 'COALESCE(' . $this->getConnection()->getQueryGrammar()->wrap(
            $this->getTable() . '.rank'
        ) . ', ' . $this->defaultCoalesceRank . ')';
    }
}
