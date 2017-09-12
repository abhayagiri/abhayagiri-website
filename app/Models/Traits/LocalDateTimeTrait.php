<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Parsedown;

trait LocalDateTimeTrait
{
    protected function getLocalDateTimeFrom($name)
    {
        $value = array_get($this->attributes, $name);
        if ($value) {
            $date = (new Carbon($value))->tz($this->getLocalTimeZone());
            return $date->toDateTimeString();
        } else {
            return null;
        }
    }

    protected function setLocalDateTimeTo($name, $value)
    {
        if ($value) {
            $localTime = new Carbon($value, $this->getLocalTimeZone());
            $value = $localTime->tz('UTC')->toDateTimeString();
        } else {
            $value = null;
        }
        $this->attributes[$name] = $value;
    }

    protected function getLocalTimeZone()
    {
        // TODO should be local to user
        return 'America/Los_Angeles';
    }
}
