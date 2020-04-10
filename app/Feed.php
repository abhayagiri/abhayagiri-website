<?php

namespace App;

use FeedWriter\ATOM;
use FeedWriter\Feed as FeedWriterFeed;
use FeedWriter\Item;
use FeedWriter\RSS2;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use App\Util;

/**
 * A simplified wrapper class for Abhayagiri feeds.
 */
class Feed extends FeedWriterFeed
{
    /**
     * The language.
     *
     * @var string
     */
    protected $lng;

    /**
     * The top-level page of this feed, e.g., news, talks, etc.
     *
     * @var string
     */
    protected $page;

    /**
     * The type of feed, e.g., 'atom' or 'rss'.
     *
     * @var string
     */
    protected $type;

    /**
     * Constructor.
     *
     * @param  string  $page
     * @param  string  $type
     * @param  string  $lng
     */
    public function __construct(string $page, string $type = 'atom', ?string $lng = null)
    {
        if ($type === 'atom') {
            $version = Feed::ATOM;
        } elseif ($type === 'rss') {
            $version = Feed::RSS2;
        } else {
            throw new InvalidArgumentException('Expected $type to by atom or rss');
        }
        parent::__construct($version);
        $this->page = $page;
        $this->type = $type;
        $this->lng = $lng ?? App::getLocale();
        $this->setCommonElements();
    }

    /**
     * Create a new item from a model.
     *
     * Models should have the following fields defined:
     *
     * - id
     * - updated_at
     * - title or title_en/title_th
     * - body_html or body_html_en/body_html_th
     *
     * @param  Illuminate\Database\Eloquent\Model  $model
     * @return FeedWriter\Item
     */
    public function createNewItemFromModel(Model $model): Item
    {
        $item = $this->createNewItem();
        $url = URL::to($model->getPath($this->lng));
        $item->setId($url, true);
        $item->setLink($url);
        $item->setDate($this->normalizeDate($model->updated_at ?? $model->posted_at));
        $item->setTitle($model->title ?? tp($model, 'title', $this->lng));
        $body = $model->body_html ?? tp($model, 'body_html', $this->lng);
        $item->setDescription($this->fixLinks($body));
        return $item;
    }

    /**
     * Set the author of the item.
     *
     * @param  FeedWriter\Item  $item
     * @param  string $author
     * @return self
     */
    public function setItemAuthor(Item $item, string $author): self
    {
        if ($this->type === 'rss') {
            // http://www.lowter.com/blogs/2008/2/9/rss-dccreator-author
            $item->addElement('dc:creator', $author);
        } else {
            $item->setAuthor($author);
        }
        return $this;
    }

    /**
     * Set the author of the item to the author of the model.
     *
     * @param  FeedWriter\Item  $item
     * @param  Illuminate\Database\Eloquent\Model  $model
     * @return self
     */
    public function setItemAuthorFromModel(Item $item, Model $model): self
    {
        return $this->setItemAuthor($item, tp($model->author, 'title', $this->lng));
    }

    /**
     * Set the image URL of a media path.
     *
     * @param  FeedWriter\Item  $item
     * @param  string  $path
     * @return self
     */
    public function setItemImageFromMedia(Item $item, string $path): self
    {
        $item->addElement('media:content', null, [
            'url' => URL::to($path),
            'medium' => 'image',
        ]);
        return $this;
    }

    /**
     * Set the media enclosure with a media path.
     *
     * @param  FeedWriter\Item  $item
     * @param  string  $path
     * @return self
     */
    public function setItemEnclosureFromMedia(Item $item, string $path): self
    {
        $enclosureUrl = URL::to($path);
        $enclosureSize = $this->getMediaSize($path);
        $item->addEnclosure($enclosureUrl, $enclosureSize, 'audio/mpeg');
        return $this;
    }

    /**
     * Replace paths in $html with full URLs.
     *
     * @param  null|string  $html
     * @return string
     */
    protected function fixLinks(?string $html): string
    {
        $re = '/(<(?:a|img)(?:.+?)(?:href|src)=[\'"])(.+?)([\'"](?:.*?)>)/';
        return preg_replace_callback($re, function ($matches) {
            $href = $matches[2];
            if (starts_with($href, '/')) {
                $href = URL::to($href);
            }
            return $matches[1] . $href . $matches[3];
        }, $html);
    }

    /**
     * Return the size of the media file at $path.
     *
     * TODO This is broken as we're using Digital Ocean Spaces.
     *
     * @param  string  $path
     * @return int
     */
    protected static function getMediaSize(string $path): int
    {
        $path = public_path('media/' . $path);
        if (file_exists($path)) {
            return filesize($path);
        } else {
            return 0;
        }
    }

    /**
     * Return the integer timestamp for a string date
     *
     * @param  string  $date
     * @return int
     */
    protected function normalizeDate(string $date): int
    {
        $previous = date_default_timezone_get();
        try {
            // date_default_timezone_set(Config::get('abhayagiri.human_timezone'));
            $date = @strtotime($date);
        } finally {
            // date_default_timezone_set($previous);
        }
        if (!is_int($date) || $date < 0) {
            $date = 0;
        }
        return $date;
    }

    /**
     * Set common elements for Abhayagiri Feeds.
     *
     * @return void
     */
    protected function setCommonElements()
    {
        $page = app('pages')->get($this->page);

        $this->setTitle(__('common.abhayagiri') . ' ' . $page->title);
        $this->setDescription(__('common.abhayagiri') . ' ' . $page->title .
                              ' ' . $page->subtitle);

        $link = URL::to(Util::localizedPath('/' . $page->slug, $this->lng));
        $this->setLink($link);

        // Take the published date to be the last 15 minutes
        $pubDate = $this->pubDate ?? floor(time()/900)*900;
        $this->setDate($pubDate);

        // See https://www.feedvalidator.org/docs/warning/MissingAtomSelfLink.html
        $this->setAtomLink($link . '.' . $this->type, 'self',
                           'application/' . $this->type . '+xml');

        if ($this->type === 'rss') {
            // The following only applies to RSS feeds
            $this->setChannelElement('language', $this->lng);
            $this->setChannelElement('pubDate', date(\DATE_RSS, $pubDate));
        }

        // Indicate that this feed has media elements
        $this->addNamespace('media', 'http://search.yahoo.com/mrss/');
    }
}
