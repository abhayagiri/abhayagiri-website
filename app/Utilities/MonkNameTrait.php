<?php

namespace App\Utilities;

use Str;

trait MonkNameTrait
{
    /**
     * Returns whether the two names are same monk name.
     *
     * @param  string  $name1
     * @param  string  $name2
     *
     * @return bool
     */
    public static function isEqualMonkName(string $name1, string $name2) : bool
    {
        return !!array_intersect(
            static::getMonkNamePermutations($name1),
            static::getMonkNamePermutations($name2)
        );
    }

    /**
     * Return parts of monk names that are similar.
     *
     * @return array
     */
    protected static function getMonkNameReplacements() : array
    {
        return [
            'luang por' => 'ajahn',
            // TODO The following is not ideal but a temporary work-around until we
            // figure out how to map title changes...
            'tan kaccana' => 'ajahn kaccana',
        ];
    }

    /**
     * Returns an array of lower-cased, ASCII permutations of the monk's name.
     *
     * @param  string  $name
     *
     * @return array
     */
    protected static function getMonkNamePermutations(string $name) : array
    {
        $multiply = function ($names, $transform) {
            return array_unique(array_merge(
                $names,
                array_map($transform, $names)
            ));
        };
        $name = trim($name);
        $result = [
            $name,
            preg_replace('/(Ñ|ñ)/', 'ny', $name)
        ];
        $result = array_map(function ($str) {
            return strtolower(Str::ascii($str));
        }, $result);
        foreach (static::getMonkNameReplacements() as $search => $replace) {
            $result = $multiply($result, function ($name) use ($search, $replace) {
                return str_replace($search, $replace, $name);
            });
        }
        return $result;
    }
}
