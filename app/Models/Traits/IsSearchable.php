<?php

namespace App\Models\Traits;

use Laravel\Scout\Searchable;

trait IsSearchable
{
    use Searchable;

    /**
     * Initialize the trait.
     *
     * @return void
     */
    public function initializeIsSearchable()
    {
        $this->append('model_basename');
    }

    /**
     * Get the model's class basename.
     *
     * @return string
     */
    public function getModelBasenameAttribute()
    {
        return kebab_case(class_basename(get_called_class()));
    }
}
