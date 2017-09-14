<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
                default:
                    return null;
            }
            $model = $className::where('id', $to->id)->first();
            if ($model) {
                $prefix = $to->lng === 'th' ? '/th' : '';
                switch ($to->type) {
                    case 'talks';
                        $prefix = $prefix ? '/new/th' : '/new';
                        return $prefix . '/talks/' .
                            $model->id . '-' .
                            rawurlencode(str_slug($model->title_en));
                    case 'Book';
                        return $prefix . '/books/' .
                            $model->id . '-' .
                            rawurlencode(str_slug($model->title));
                }
            }
        }
        return null;
    }

}
