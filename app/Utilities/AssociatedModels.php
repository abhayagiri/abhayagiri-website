<?php

namespace App\Utilities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use stdClass;

trait AssociatedModels
{
    /**
     * The chunk size for querying for associated models.
     *
     * @var int
     */
    protected static $associatedChunkSize = 1000;

    /**
     * Returns the models after and before this model, and the page this model
     * is on, in relation to a query scope. This is useful for providing
     * navigation links.
     *
     * @todo This has the potential to run n+1 queries for large datasets.
     * There are smarter alternatives to this, but this will work (for now).
     *
     * The return object has the following fields set:
     *
     *   - after
     *   - before
     *   - page
     *
     * If all are null, then there was an error. If after or before is null,
     * then $model is the first or last in the query scope.
     *
     * @param  Illuminate\Database\Eloquent\Model  $model
     * @param  Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $pageSize
     *
     * @return stdClass
     */
    public static function getAssociatedModels(
        Model $model,
        Builder $query,
        int $pageSize
    ): stdClass {
        $class = get_class($model);
        $chunkSize = static::$associatedChunkSize;
        $count = (clone $query)->count();
        for ($offset = 0; $offset < $count; $offset += $chunkSize) {
            $subQuery = (clone $query)->offset($offset)->limit($chunkSize);
            $result = static::getNaiveAssociatedModels(
                $model,
                $subQuery,
                $pageSize,
                $offset
            );
            if ($result->after || $result->before) {
                $halfOffset = null;
                if (!$result->before) {
                    if ($offset + $chunkSize < $count) {
                        $result->before = (clone $query)
                            ->offset($offset + $chunkSize)->first();
                    }
                } elseif (!$result->after) {
                    if ($offset >= $chunkSize) {
                        $result->after = (clone $query)
                            ->offset($offset - 1)->first();
                    }
                }
                return $result;
            }
        }
        return $result;
    }

    private static function getNaiveAssociatedModels(
        Model $model,
        Builder $query,
        int $pageSize,
        int $offset
    ): stdClass {
        $class = get_class($model);
        $ids = (clone $query)->pluck($model->getKeyName());
        $count = $ids->count();
        $index = $ids->search($model->getKey());
        if ($index === false || $count == 0) {
            $index = -1;
            $afterId = $beforeId = null;
        } else {
            $afterId = ($index > 0) ? $ids[$index - 1] : null;
            $beforeId = ($index < $count - 1) ? $ids[$index + 1] : null;
        }
        return (object) [
            'after' => $afterId ? $class::find($afterId) : null,
            'before' => $beforeId ? $class::find($beforeId) : null,
            'page' => ($index >= 0) ?
                      (1 + intval(($index + $offset) / $pageSize)) : null,
        ];
    }
}
