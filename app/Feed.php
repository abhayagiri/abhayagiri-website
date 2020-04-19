<?php

namespace App;

use FeedWriter\ATOM;
use FeedWriter\Feed as FeedWriterFeed;
use FeedWriter\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

/**
 * A simplified wrapper class for Abhayagiri feeds.
 */
class Feed extends FeedWriterFeed
{

    /**
     * RSS DTDs
     *
     * @var string
     */
    const MEDIA_DTD = 'http://search.yahoo.com/mrss/';
    const ITUNES_DTD = 'http://www.itunes.com/dtds/podcast-1.0.dtd';

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
     * Models should have the following defined:
     *
     * - getUrl() (see HasPath)
     * - posted_at or created_at
     * - title or title_en/title_th
     * - body_html or body_html_en/body_html_th
     *
     * @param  Illuminate\Database\Eloquent\Model  $model
     *
     * @return FeedWriter\Item
     */
    public function createNewItemFromModel(Model $model): Item
    {
        $item = $this->createNewItem();
        $item->setId($model->getUrl($this->lng, false), true);
        $item->setLink($model->getUrl($this->lng, true));
        $item->setDate($this->normalizeDate($model->posted_at ?? $model->created_at));
        $item->setTitle($model->title ?? tp($model, 'title', $this->lng));
        $body = $model->body_html ?? tp($model, 'body_html', $this->lng);
        $item->setDescription($this->fixLinks($body));
        return $item;
    }

    /**
     * Set the category.
     *
     * @param  string  $category
     * @param  string  $category
     * @param  bool  $explicit
     *
     * @return self
     */
    public function setItunesFeed(string $imageUrl, string $category, bool $explicit): self
    {
        if ($this->type !== 'rss') {
            return $this;
        }
        $this->addNamespace('itunes', static::ITUNES_DTD);
        $this->setChannelElement('itunes:owner', [
            'itunes:author' => __('common.abhayagiri_monastery'),
            'itunes:name' => __('common.abhayagiri_monastery'),
            'itunes:email' => config('abhayagiri.auth.mahapanel_admin'),
        ]);
        $this->setChannelElement('itunes:image', '', ['href' => $imageUrl]);
        $this->setChannelElement('itunes:category', '', ['text' => $category]);
        $this->setChannelElement('itunes:explicit', $explicit ? 'yes' : 'no');
        return $this;
    }

    /**
     * Set the author of the item.
     *
     * @param  FeedWriter\Item  $item
     * @param  string $author
     *
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
     * Set the author of the item from the model.
     *
     * @param  FeedWriter\Item  $item
     * @param  Illuminate\Database\Eloquent\Model  $model
     *
     * @return self
     */
    public function setItemAuthorFromModel(Item $item, Model $model): self
    {
        return $this->setItemAuthor($item, tp($model->author, 'title', $this->lng));
    }

    /**
     * Set the image of the item from the model.
     *
     * @param  FeedWriter\Item  $item
     * @param  Illuminate\Database\Eloquent\Model  $model
     * @param  string  $preset
     * @param  string  $format
     *
     * @return self
     */
    public function setItemImageFromModel(
        Item $item,
        Model $model,
        string $preset,
        string $format
    ): self {
        // This isn't ideal best but it works.
        $url = route($model->getTable() . '.image', [$model, $preset, $format]);
        $item->addElement('media:content', null, [
            'url' => $url,
            'medium' => 'image',
            'type' => 'image/' . ($format === 'jpg' ? 'jpeg' : $format),
        ]);
        return $this;
    }

    /**
     * Replace paths in $html with full URLs.
     *
     * @param  string|null  $html
     *
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
     * Return the integer timestamp for a string date
     *
     * @param  string  $date
     *
     * @return int
     */
    protected function normalizeDate(string $date): int
    {
        $previous = date_default_timezone_get();
        try {
            // We're currently storing dates in UTC so we don't need this.
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
        $title = __('common.abhayagiri') . ' ' . $page->title;
        $description = __('common.abhayagiri') . ' ' . $page->title .
                       ($page->subtitle ? (' ' . $page->subtitle) : '');
        $linkUrl = URL::to(Util::localizedPath('/' . $page->slug, $this->lng));
        $imageUrl = URL::to('/img/ui/header-' . $this->lng . '.jpg');
        // Take the feed published date to be the last 15 minutes
        $pubDate = $this->pubDate ?? floor(time()/900)*900;

        $this->setTitle($title);
        $this->setDescription($description);
        $this->setLink($linkUrl);
        $this->setImage($imageUrl, $title, $linkUrl);
        $this->setDate($pubDate);

        // See https://www.feedvalidator.org/docs/warning/MissingAtomSelfLink.html
        $this->setAtomLink(
            $linkUrl . '.' . $this->type,
            'self',
            'application/' . $this->type . '+xml'
        );

        if ($this->type === 'rss') {
            // The following only applies to RSS feeds
            $this->setChannelElement('language', $this->lng);
            $this->setChannelElement('pubDate', date(\DATE_RSS, $pubDate));
        }

        // Indicate that this feed has media elements
        $this->addNamespace('media', static::MEDIA_DTD);
    }
}
