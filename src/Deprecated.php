<?php

namespace Abhayagiri;

require_once __DIR__ . '/../public/php/db.php';
require_once __DIR__ . '/../public/php/func.php';

class Deprecated {

    private static $db;

    public static function getDB() {
        if (static::$db === null) {
            static::$db = new \DB(Config::getConfig());
        }
        return static::$db;
    }

    public static function getFunc($language = 'English') {
        return new \Func(static::getDB(), $language);
    }

}

?>
