<?php

namespace App;

use Illuminate\Support\Facades\DB;

class SettingsException extends \Exception {};

class Settings {

    /**
     * Get a setting.
     *
     * @param string $key
     * @return mixed
     */
    public static function get($key)
    {
        $result = DB::table('settings')->where('key_', '=', $key)->pluck('value');
        if ($result->count() == 1) {
            return json_decode($result->first());
        } elseif ($result->count() == 0) {
            throw new SettingsException("No such key $key");
        } else {
            throw new SettingsException("Key $key returned multiple values");
        }
    }

    /**
     * Set (an existing) a setting.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        DB::table('settings')->where('key_', '=', $key)->update([
            'value' => json_encode($value)
        ]);
    }
}
