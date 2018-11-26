<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Mremi\UrlShortener\Model\Link;
use Mremi\UrlShortener\Provider\Bitly\BitlyProvider;
use Mremi\UrlShortener\Provider\Bitly\OAuthClient;
use Mremi\UrlShortener\Provider\Bitly\GenericAccessTokenAuthenticator;
use Mremi\UrlShortener\Provider\Google\GoogleProvider;
use Venturecraft\Revisionable\RevisionableTrait;

use App\Legacy;

class Danalist extends Model
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use Traits\LocalDateTimeTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'danalist';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_listed_at'];

    /**
     * The attributes that should not be revisioned.
     *
     * @var array
     */
    protected $dontKeepRevisionOf = [
        'short_link', 'check_translation', 'last_listed_at', 'deleted_at',
    ];

    /**
     * Override to store the creation as a revision
     *
     * @var boolean
     */
    protected $revisionCreationsEnabled = true;

    /**********
     * Scopes *
     **********/

    public function scopePublic($query)
    {
        return $query->where('danalist.listed', true);
    }

    /**************************
     * Accessors and Mutators *
     **************************/

    public function setListedAttribute($value)
    {
        $this->attributes['listed'] = $value;
        if ($value) {
            $this->setAttribute('last_listed_at', Carbon::now());
        }
    }

    /***********************
     * Short Link Creation *
     ***********************/

    /**
     * Make a short link using bit.ly.
     *
     * BITLY_ACCESS_TOKEN must be defined in .env.
     *
     * @param string $link
     * @return string or null
     */
    public static function makeShortLink($link)
    {
        $bitlyToken = env('BITLY_ACCESS_TOKEN');
        if (!$bitlyToken) {
            Log::warning('BITLY_ACCESS_TOKEN not defined in .env');
            return null;
        }
        $linkObj = new Link;
        $linkObj->setLongUrl($link);
        $auth = new GenericAccessTokenAuthenticator($bitlyToken);
        $bitlyProvider = new BitlyProvider($auth, [
            'connect_timeout' => 1,
            'timeout' => 1,
        ]);
        try {
            $bitlyProvider->shorten($linkObj);
        } catch (\Exception $e) {
            Log::error('Got exception when requesting a short link for ' . $link);
            Log::error($e->getMessage());
            return null;
        }
        $shortLink = $linkObj->getShortUrl();
        if ($shortLink) {
            Log::info('Made short link ' . $shortLink . ' from ' . $link);
            return $shortLink;
        } else {
            Log::error('Could not make a short link for ' . $link);
            return null;
        }
    }

    /**
     * Makes and sets the short_link attribute from the link_attribute.
     *
     * @return void
     */
    public function setShortLinkFromLink()
    {
        $link = $this->getAttribute('link');
        if ($link) {
            $shortLink = static::makeShortLink($link);
            if (!$shortLink) {
                $shortLink = $link;
            }
            $this->setAttribute('short_link', $shortLink);
        }
    }

    /**
     * The "booting" method of this model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->getAttribute('short_link')) {
                $model->setShortLinkFromLink();
            }
        });
        static::updating(function ($model) {
            if (!$model->getAttribute('short_link') || $model->isDirty('link')) {
                $model->setShortLinkFromLink();
            }
        });
    }

    /*********
     * Other *
     *********/

    public function getLinkColumnHtml()
    {
        return '<a href="' . e($this->link) . '" target="_blank">' .
            e($this->link) . '</a>';
    }

    public static function getMacroHtml($lng)
    {
        return View::make('subpages/danalist', [
            'danalist' => static::public()->orderBy('title')->get(),
            'lng' => $lng,
        ])->render();
    }
}
