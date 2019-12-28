<?php

namespace App\Search;

use Algolia\ScoutExtended\Searchable\Aggregator;
use App\Models\Book;
use App\Models\News;
use App\Models\Reflection;
use App\Models\Subpage;
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
    ];

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable()
    {
        if (array_key_exists(Searchable::class, class_uses_recursive($this->model))) {
            return $this->model->shouldBeSearchable();
        }
    }
}
