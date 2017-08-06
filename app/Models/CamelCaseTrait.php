<?php

namespace App\Models;

trait CamelCaseTrait
{

    /**
     * Changes the default array/JSON output to use camelcase keys.
     *
     * @see https://stackoverflow.com/questions/27867569/laravel-eloquent-serialization-how-to-rename-property
     */
    public function toArray()
    {
        $array = parent::toArray();
        $camelArray = array();
        foreach ($array as $name => $value) {
            $camelArray[camel_case($name)] = $value;
        }
        return $camelArray;
    }
}
