<?php

namespace App\Models\Observers\Talk;

use App\Models\Talk;

class SetId3DataObserver
{
    public function saved(Talk $talk)
    {
        $talk->updateId3Tags();
    }
}
