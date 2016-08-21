<?php

namespace App;

use DB;

class Util
{

    /**
     * Return an array of database tables.
     *
     * @return array
     */
    static public function getTables()
    {
        $result = [];
        foreach (DB::select('SHOW TABLES') as $table) {
            foreach ($table as $key => $name) {
                $result[] = $name;
            }
        }
        return $result;
    }
}
