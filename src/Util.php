<?php

namespace Abhayagiri;

class Util {

    public static function getVersion() {
        return trim(exec('git log -n1 --pretty="%h - %ci - %s" HEAD'));
    }
}

?>
