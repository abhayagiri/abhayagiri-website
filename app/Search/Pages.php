<?php

namespace App\Search;

use Algolia\ScoutExtended\Searchable\Aggregator;
use App\Models\Album;
use App\Models\Book;
use App\Models\ContactOption;
use App\Models\News;
use App\Models\Reflection;
use App\Models\Resident;
use App\Models\Subpage;
use App\Models\Tale;
use App\Models\Talk;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;

class Pages extends Aggregator
{
    /**
     * The names of the models that should be aggregated.
     *
     * @var string[]
     */
    protected $models = [
        Album::class,
        Book::class,
        ContactOption::class,
        News::class,
        Reflection::class,
        Resident::class,
        Subpage::class,
        Tale::class,
        Talk::class,
    ];

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable()
    {
        $searchable = false;
        if (method_exists($this->model, 'shouldBeSearchable')) {
            $searchable = $this->model->shouldBeSearchable();
            if ($searchable && app('env') !== 'production' &&
                method_exists($this->model, 'shouldBeTestingSearchable')) {
                $searchable = $this->model->shouldBeTestingSearchable();
            }
        }
        return $searchable;
    }

    /**
     * Return the expected number of models to be imported for each model class.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getExpectedImportCount(): Collection
    {
        $log = new Collection();
        $models = (new static)->getModels();
        foreach ($models as $model) {
            $log[$model] = 0;
            $chunkSize = config('scout.chunk.searchable', 500);
            $instance = new $model;
            $instance
                ->orderBy($instance->getKeyName())
                ->chunk($chunkSize, function ($dbModels) use (&$log, $model) {
                    $count = $dbModels->map(function ($dbModel) {
                        return static::create($dbModel);
                    })->filter->shouldBeSearchable()->map(function ($searchModel) {
                        return count($searchModel->model->splitText(
                            $searchModel->model->toSearchableArray()['text']
                        ));
                    })->reduce(function ($sum, $n) {
                        return $sum + $n;
                    }, 0);
                    $log[$model] += $count;
                });
        }
        return $log;
    }
}
