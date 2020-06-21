<?php

namespace App\Search;

use Algolia\ScoutExtended\Searchable\Aggregator;
use App\Models\Book;
use App\Models\News;
use App\Models\Reflection;
use App\Models\Subpage;
use App\Models\Tale;
use Laravel\Scout\Searchable;

class Pages extends Aggregator
{
    /**
     * The names of the models that should be aggregated.
     *
     * @var string[]
     */
    protected $models = [
        Book::class,
        News::class,
        Reflection::class,
        Subpage::class,
        Tale::class,
    ];

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable()
    {
        $searchable = false;
        if (array_key_exists(Searchable::class, class_uses_recursive($this->model))) {
            $searchable = $this->model->shouldBeSearchable();
            if ($searchable && app('env') !== 'production') {
                $searchable = $this->model->shouldBeTestingSearchable();
            }
        }
        return $searchable;
    }
}
