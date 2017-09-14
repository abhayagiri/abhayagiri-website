<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Redirect extends Model
{
    protected $fillable = ['from', 'to'];

    static public function createFromOld($path, $options)
    {
        self::create([
            'from' => $path,
            'to' => json_encode(array_merge($options, [ 'lng' => 'en' ]))
        ]);
        self::create([
            'from' => 'th/' . $path,
            'to' => json_encode(array_merge($options, [ 'lng' => 'th' ]))
        ]);
    }

    static public function getRedirectFromPath($path)
    {
        $redirect = self::where('from', $path)->first();
        if ($redirect) {
            $to = json_decode($redirect->to);
            switch ($to->type) {
                case 'Basic';
                    return $to->url;
                case 'talks';
                    $className = '\App\Models\Talk';
                    break;
                case 'Book';
                    $className = '\App\Models\Book';
                    break;
                case 'News';
                    $className = '\App\Models\News';
                    break;
                default:
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
        } else {
            return null;
        }
    }
}
