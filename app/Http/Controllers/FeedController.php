<?php

namespace App\Http\Controllers;

use App\Feed;
use App\Models\News;
use App\Models\Reflection;
use App\Models\Tale;
use App\Models\Talk;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class FeedController extends Controller
{
    /**
     * The maximum number of feed items.
     *
     * @var int
     */
    protected $maxItems = 20;

    /**
     * Return an ATOM feed for news.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function newsAtom(Request $request): Response
    {
        return $this->newsFeed($request, 'atom');
    }

    /**
     * Return an RSS2 feed for news.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function newsRss(Request $request): Response
    {
        return $this->newsFeed($request, 'rss');
    }

    /**
     * Return an ATOM feed for reflections.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function reflectionsAtom(Request $request): Response
    {
        return $this->reflectionsFeed($request, 'atom');
    }

    /**
     * Return an RSS2 feed for reflections.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function reflectionsRss(Request $request): Response
    {
        return $this->reflectionsFeed($request, 'rss');
    }

    /**
     * Return an ATOM feed for tales.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function talesAtom(Request $request): Response
    {
        return $this->talesFeed($request, 'atom');
    }

    /**
     * Return an RSS2 feed for tales.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function talesRss(Request $request): Response
    {
        return $this->talesFeed($request, 'rss');
    }

    /**
     * Return an ATOM feed for talks.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function talksAtom(Request $request): Response
    {
        return $this->talksFeed($request, 'atom');
    }

    /**
     * Return an RSS2 feed for talks.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function talksRss(Request $request): Response
    {
        return $this->talksFeed($request, 'rss');
    }

    /**
     * Return a response for a feed;
     *
     * @param  App\Feed  $feed
     * @param  string  $type
     *
     * @return \Illuminate\Http\Response
     */
    protected function feedResponse(Feed $feed, string $type)
    {
        $response = new Response($feed->generateFeed());
        if ($type == 'atom') {
            $response->header('Content-Type', 'application/atom+xml; charset=utf-8');
        } else {
            $response->header('Content-Type', 'application/rss+xml; charset=utf-8');
        }
        return $response;
    }

    /**
     * Return a feed for news.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     *
     * @return \Illuminate\Http\Response
     */
    protected function newsFeed(Request $request, string $type): Response
    {
        $feed = new Feed('news', $type);
        // Note: this is *not* commonOrder as we want the news.rss to go out
        // chronologically.
        $news = News::public()->postedAtOrder();
        if (App::isLocale('th')) {
            $news = $news->withThai();
        }
        $news = $news->limit($this->maxItems);
        $news->get()->each(function ($news) use ($feed) {
            $item = $feed->createNewItemFromModel($news);
            $feed->setItemAuthor($item, __('common.abhayagiri_monastery'))
                 ->setItemImageFromModel($item, $news, 'rss', 'jpg');
            $feed->addItem($item);
        });
        return $this->feedResponse($feed, $type);
    }

    /**
     * Return a feed for reflections.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     *
     * @return \Illuminate\Http\Response
     */
    protected function reflectionsFeed(Request $request, string $type): Response
    {
        $feed = new Feed('reflections', $type);
        $reflections = Reflection::public()->commonOrder()->with('author')
                                      ->limit($this->maxItems);
        $reflections->get()->each(function ($reflection) use ($feed) {
            $item = $feed->createNewItemFromModel($reflection);
            $feed->setItemAuthorFromModel($item, $reflection)
                 ->setItemImageFromModel($item, $reflection->author, 'icon', 'jpg');
            $feed->addItem($item);
        });
        return $this->feedResponse($feed, $type);
    }

    /**
     * Return a feed for tales.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     *
     * @return \Illuminate\Http\Response
     */
    protected function talesFeed(Request $request, string $type): Response
    {
        $feed = new Feed('tales', $type);
        $tales = Tale::public()->commonOrder();
        if (App::isLocale('th')) {
            $tales = $tales->withThai();
        }
        $tales = $tales->with('author')->limit($this->maxItems);
        $tales->get()->each(function ($tale) use ($feed) {
            $item = $feed->createNewItemFromModel($tale);
            $feed->setItemAuthorFromModel($item, $tale)
                 ->setItemImageFromModel($item, $tale, 'rss', 'jpg');
            $feed->addItem($item);
        });
        return $this->feedResponse($feed, $type);
    }

    /**
     * Return a feed for talks.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     *
     * @return \Illuminate\Http\Response
     */
    protected function talksFeed(Request $request, string $type): Response
    {
        $feed = new Feed('talks', $type);
        $feed->setItunesFeed(
            Url::to('/media/images/itunes-logo.jpg'),
            'Religion & Spirituality',
            false
        );
        $mainPlaylistGroup = Talk::getLatestPlaylistGroup('main');
        $talks = Talk::latestTalks($mainPlaylistGroup)
                     ->whereNotNull('media_path')
                     ->with('author')->postedAtOrder()->limit($this->maxItems);
        $talks->get()->each(function ($talk) use ($feed) {
            $item = $feed->createNewItemFromModel($talk);
            $feed->setItemAuthorFromModel($item, $talk)
                 ->setItemImageFromModel($item, $talk->author, 'icon', 'jpg');
            $mp3Url = route('talks.audio', [$talk, 'mp3']);
            // TODO: We need to get the size of this into the database.
            $item->addEnclosure($mp3Url, 0, 'audio/mpeg');
            $feed->addItem($item);
        });
        return $this->feedResponse($feed, $type);
    }
}
