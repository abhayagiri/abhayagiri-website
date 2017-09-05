<?php

namespace App\Models;

trait CamelCaseTrait
{
    /**
     * Returns toArray with camelcase keys.
     *
     * To use, add the following method to the model:
     *
     *     public function toArray()
     *     {
     *         return camelizeArray(parent:toArray());
     *     }
     *
     * @see https://stackoverflow.com/questions/27867569/laravel-eloquent-serialization-how-to-rename-property
     */
    public function camelizeArray($array)
    {
        $camelArray = array();
        foreach ($array as $name => $value) {
            $camelArray[camel_case($name)] = $value;
        }
        return $camelArray;
    }
}
