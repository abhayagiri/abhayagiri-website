<?php

namespace App\Search;

use App\Models\Subpage;
use Laravel\Scout\Searchable;
use Algolia\ScoutExtended\Searchable\Aggregator;

class Pages extends Aggregator
{
    /**
     * The names of the models that should be aggregated.
     *
     * @var string[]
     */
    protected $models = [
        Subpage::class,
    ];

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable()
    {
        if (array_key_exists(Searchable::class, class_uses($this->model))) {
            return $this->model->shouldBeSearchable();
        }
    }
}
