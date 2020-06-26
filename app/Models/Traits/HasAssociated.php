<?php

namespace App\Models\Traits;

use App\Util;
use stdClass;

trait HasAssociated
{
    /**
     * Get the associated models and page.
     *
     * @see \Utilities\DatabaseTrait::getAssociatedModels()
     *
     * @param  int  $pageSize
     *
     * @return stdClass
     */
    public function getAssociated(int $pageSize = 10): stdClass
    {
        return Util::getAssociatedModels(
            $this,
            static::public()->commonOrder(),
            $pageSize
        );
    }
}
