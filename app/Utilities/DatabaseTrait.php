<?php

namespace App\Utilities;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait DatabaseTrait
{
    /**
     * Returns duplicate entries in a table for a particular column.
     *
     * The return object is a collection of rows with the data columns $key and
     * $column.
     *
     * @param  string  $table
     * @param  string  $column  the column to check for uniqueness
     * @param  string  $key     the primary key column
     * @return \Illuminate\Support\Collection
     */
    public static function getTableDuplicates(string $table, string $column,
                                              string $key = 'id') : Collection
    {
        $duplicates = DB::table($table)
            ->select($column)->groupBy($column)
            ->having(DB::raw('COUNT(' . $column . ')'), '>', 1)
            ->get();
        if ($duplicates) {
            return DB::table($table)->select($key, $column)
                ->whereIn($column, $duplicates->pluck($column))
                ->orderBy($column)->orderBy($key)
                ->get();
        } else {
            return $duplicates;
        }
    }
}
