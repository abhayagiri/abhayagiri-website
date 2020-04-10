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
     * @return \Illuminate\Http\Response
     */
    protected function newsFeed(Request $request, string $type): Response
    {
        $feed = new Feed('news', $type);
        $news = News::public();
        if (App::isLocale('th')) {
            $news = $news->withThai();
        }
        $news->postOrdered()->limit($this->maxItems)->get()
                            ->each(function($news) use ($feed) {
            $item = $feed->createNewItemFromModel($news);
            $feed->setItemAuthor($item, __('common.abhayagiri_monastery'));
            $feed->addItem($item);
        });
        return $this->feedResponse($feed, $type);
    }

    /**
     * Return a feed for reflections.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    protected function reflectionsFeed(Request $request, string $type): Response
    {
        $feed = new Feed('reflections', $type);
        Reflection::public()->with('author')->postOrdered()->limit($this->maxItems)
                     ->get()->each(function($reflection) use ($feed) {
            $item = $feed->createNewItemFromModel($reflection);
            $feed->setItemAuthorFromModel($item, $reflection)
                 ->setItemImageFromMedia($item, $reflection->author->image_url);
            $feed->addItem($item);
        });
        return $this->feedResponse($feed, $type);
    }

    /**
     * Return a feed for tales.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    protected function talesFeed(Request $request, string $type): Response
    {
        $feed = new Feed('tales', $type);
        $tales = Tale::public();
        if (App::isLocale('th')) {
            $tales = $tales->withThai();
        }
        $tales->with('author')->postOrdered()->limit($this->maxItems)
              ->get()->each(function($tale) use ($feed) {
            $item = $feed->createNewItemFromModel($tale);
            $feed->setItemAuthorFromModel($item, $tale)
                 ->setItemImageFromMedia($item, $tale->image_url);
            $feed->addItem($item);
        });
        return $this->feedResponse($feed, $type);
    }

    /**
     * Return a feed for talks.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    protected function talksFeed(Request $request, string $type): Response
    {
        $feed = new Feed('talks', $type);
        $mainPlaylistGroup = Talk::getLatestPlaylistGroup('main');
        Talk::latestTalks($mainPlaylistGroup)->whereNotNull('media_path')
                ->with('author')->postOrdered()->limit($this->maxItems)
                ->get()->each(function($talk) use ($feed) {
            $item = $feed->createNewItemFromModel($talk);
            $feed->setItemAuthorFromModel($item, $talk)
                 ->setItemImageFromMedia($item, $talk->author->image_url)
                 ->setItemEnclosureFromMedia($item, $talk->media_path);
            $feed->addItem($item);
        });
        return $this->feedResponse($feed, $type);
    }
}
