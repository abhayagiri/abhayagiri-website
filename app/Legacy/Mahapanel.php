<?php

namespace App\Legacy;

use DB;

class Mahapanel
{

    /**
     * Return a builder of mahapanel pages.
     *
     * @return Illuminate\Database\Query\Builder
     */
    static public function mahapanelPages()
    {
        return DB::table('pages')
            ->where('mahapanel', '=', 'yes')
            ->orderBy('title');
    }
}

