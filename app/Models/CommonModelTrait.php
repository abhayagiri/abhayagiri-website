<?php

namespace App\Models;

use Carbon\Carbon;
use Parsedown;

trait CommonModelTrait
{
    protected function getLocalDateTimeAttribute($name)
    {
        $value = array_get($this->attributes, $name);
        if ($value) {
            $date = (new Carbon($value))->tz($this->getLocalTimeZone());
            return $date->toDateTimeString();
        } else {
            return null;
        }
    }

    protected function setLocalDateTimeAttribute($name, $value)
    {
        if ($value) {
            $localTime = new Carbon($value, $this->getLocalTimeZone());
            $this->attributes[$name] = $localTime->tz('UTC')->toDateTimeString();
        } else {
            return null;
        }
    }

    protected function getLocalTimeZone()
    {
        // TODO should be local to user
        return 'America/Los_Angeles';
    }

    protected function getMediaUrlAttribute($name)
    {
        $value = array_get($this->attributes, $name);
        return $value ? '/media/' . $this->encodePath($value) : null;
    }

    protected function encodePath($path)
    {
        return implode('/', array_map('urlencode', explode('/', $path)));
    }

    /**
     * Return a markdown attribute as HTML.
     *
     * @param string name
     * @return string
     */
    protected function getHtmlFromMarkdownAttribute($name)
    {
        $value = array_get($this->attributes, $name);
        if ($value) {
            return (new Parsedown())->text($value);
        } else {
            return null;
        }
    }
}
