<?php

namespace App\Models\Traits;

use Carbon\Carbon;

trait LocalDateTimeTrait
{
    protected function setLocalDateTimeTo($name, $value)
    {
        if ($value) {
            $localTime = new Carbon($value, $this->getLocalTimeZone());
            $value = $localTime->tz('UTC')->toDateTimeString();
        } else {
            $value = null;
        }
        $this->attributes[$name] = $value;
        return $value;
    }

    protected function getLocalTimeZone()
    {
        // TODO should be local to user
        return 'America/Los_Angeles';
    }
}
