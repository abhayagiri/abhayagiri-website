<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Id3WriterHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'getID3Writer';
    }
}
