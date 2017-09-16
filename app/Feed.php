<?php

namespace App;

use FeedWriter\RSS2;
use Illuminate\Support\Facades\URL;

use App\Models\News;
use App\Models\Reflection;
use App\Models\Talk;

class Feed
{
    public static function getAudioFeed()
    {
        $feed = new RSS2;
        $feed->setTitle('Abhayagiri Audio');
        $feed->setDescription('Abhayagiri Dhamma Talks');
        static::addCommonToFeed($feed, 'audio');

        $talks = Talk::where('type_id', 2)
            ->public()->latestVisible()->latest()
            ->with('author')
            ->limit(100)
            ->get();

        foreach ($talks as $talk) {
            $row = $talk->toLegacyArray('English');
            $row['link'] = $talk->getPath();
            $item = $feed->createNewItem();
            static::addCommonToItemFromRow($item, $row, 'audio');
            $enclosureUrl = URL::to($row['media_url']);
            $enclosureSize = static::getMediaSize($row['media_url']);
            $item->addEnclosure($enclosureUrl, $enclosureSize, 'audio/mpeg');
            $feed->addItem($item);
        };

        return $feed->generateFeed();
    }

    public static function getNewsFeed()
    {
        $feed = new RSS2;
        $feed->setTitle('Abhayagiri News');
        $feed->setDescription('Abhayagiri News');
        static::addCommonToFeed($feed, 'news');

        $newss = News::public()
            ->latest()
            ->limit(100)
            ->get();

        foreach ($newss as $news) {
            $row = $news->toLegacyArray('English');
            $row['link'] = $news->getPath();
            $item = $feed->createNewItem();
            static::addCommonToItemFromRow($item, $row, 'news');
            $feed->addItem($item);
        }

        return $feed->generateFeed();
    }

    public static function getReflectionsFeed()
    {
        $feed = new RSS2;
        $feed->setTitle('Abhayagiri Reflections');
        $feed->setDescription('Abhayagiri Reflections');
        static::addCommonToFeed($feed, 'reflections');

        $reflections = Reflection::public()
            ->latest()
            ->limit(100)
            ->get();

        foreach ($reflections as $reflection) {
            $row = $reflection->toLegacyArray('English');
            $row['link'] = $reflection->getPath();
            $item = $feed->createNewItem();
            static::addCommonToItemFromRow($item, $row, 'reflections');
            $feed->addItem($item);
        }

        return $feed->generateFeed();
    }

    protected static function addCommonToItemFromRow($item, $row, $type)
    {
        $item->setTitle($row['title']);
        $item->setDescription($row['body']);
        $item->setId($row['link'], true);
        $item->setLink($row['link']);
        $item->setDate(static::normalizeDate($row['date']));
        if (array_key_exists('author', $row)) {
            $item->setAuthor($row['author']);
            $item->addElement('dc:creator', $row['author']);
            $item->addElement('media:content', null, [
                'url' => URL::to($row['author_image_url']),
                'medium' => 'image',
            ]);
        }
    }

    protected static function addCommonToFeed($feed, $type)
    {
        $feed->addNamespace('media', 'http://search.yahoo.com/mrss/');
        $feed->setLink(\URL::to('/' . $type));
        $feed->setChannelElement('language', 'en-US');
        // Take the published date to be the last 15 minutes
        $pubDate = floor(time()/900)*900;
        $feed->setDate($pubDate);
        $feed->setChannelElement('pubDate', date(\DATE_RSS, $pubDate));
    }

    protected static function normalizeDate($date)
    {
        date_default_timezone_set('America/Los_Angeles');
        $date = strtotime($date);
        date_default_timezone_set(\Config::get('app.timezone'));
        return $date;
    }

    protected static function getMediaSize($path)
    {
        $path = public_path('media/' . $path);
        if (file_exists($path)) {
            return filesize($path);
        } else {
            return 0;
        }
    }
}
