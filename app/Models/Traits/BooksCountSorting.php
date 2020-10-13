<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BooksCountSorting
{
    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrderedByBooksCount(Builder $query)
    {
        return $query->has('books')
            ->withCount('books')
            ->orderBy('books_count', 'desc');
    }
}
