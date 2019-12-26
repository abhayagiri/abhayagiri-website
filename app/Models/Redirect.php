<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Redirect extends Model
{
    protected $fillable = ['from', 'to'];

    public static function createFromOld($path, $options)
    {
        static::create([
            'from' => $path,
            'to' => json_encode(array_merge($options, [ 'lng' => 'en' ]))
        ]);
        static::create([
            'from' => 'th/' . $path,
            'to' => json_encode(array_merge($options, [ 'lng' => 'th' ]))
        ]);
    }

    protected static $redirectTypes = [
        'Book' => '\App\Models\Book',
        'News' => '\App\Models\News',
        'Playlist' => '\App\Models\Playlist',
        'Reflection' => '\App\Models\Reflection',
        'Resident' => '\App\Models\Resident',
        'Subpage' => '\App\Models\Subpage',
        'talks' => '\App\Models\Talk',
    ];

    public static function getRedirectFromPath($path)
    {
        $redirect = static::where('from', $path)->first();
        if (!$redirect) {
            return null;
        }
        $to = json_decode($redirect->to);
        if ($to->type === 'Basic') {
            return $to->url;
        }
        $className = array_get(static::$redirectTypes, $to->type);
        if (!$className) {
            Log::error('Unknown type for redirect ' . $redirect->to);
            return null;
        }
        $model = $className::where('id', $to->id)->first();
        if ($model) {
            return $model->getPath($to->lng);
        } else {
            Log::error('Cannot find record for redirect ' . $redirect->to);
            return null;
        }
    }
}
