<?php

namespace App\Models\Traits;

use App\Models\Observers\Talk\SetId3DataObserver;
use App\Models\Talk;

/**
 * Note: thist trait also requires MediaPathTrait.
 */
trait TalkObserversTrait
{
    public static function bootTalkObserversTrait()
    {
        Talk::observe(SetId3DataObserver::class);
    }
}
