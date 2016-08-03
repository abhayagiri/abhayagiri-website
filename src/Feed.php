<?php

namespace Abhayagiri;

use FeedWriter\RSS2;

class Feed
{
    public static function getAudioFeed()
    {
        $func = new Func();

        $feed = new RSS2;
        $feed->setTitle('Abhayagiri Audio');
        $feed->setDescription('Abhayagiri Dhamma Talks');
        static::addCommonToFeed($feed, 'audio');

        $data = $func->entry('audio', 100);
        foreach ($data as $row) {
            $item = $feed->createNewItem();
            static::addCommonToItemFromRow($item, $row, 'audio');
            $enclosureUrl = getWebRoot() . '/media/audio/' . $row['mp3'];
            $enclosureSize = static::getMediaSize($row['mp3']);
            $item->setEnclosure($enclosureUrl, $enclosureSize, 'audio/mpeg');
            $feed->addItem($item);
        }

        return $feed->generateFeed();
    }

    public static function getNewsFeed()
    {
        $func = new Func();

        $feed = new RSS2;
        $feed->setTitle('Abhayagiri News');
        $feed->setDescription('Abhayagiri News');
        static::addCommonToFeed($feed, 'news');

        $data = $func->entry('news', 100);
        foreach ($data as $row) {
            $item = $feed->createNewItem();
            static::addCommonToItemFromRow($item, $row, 'news');
            $feed->addItem($item);
        }

        return $feed->generateFeed();
    }

    public static function getReflectionsFeed()
    {
        $func = new Func();

        $feed = new RSS2;
        $feed->setTitle('Abhayagiri Reflections');
        $feed->setDescription('Abhayagiri Reflections');
        static::addCommonToFeed($feed, 'reflections');

        $data = $func->entry('reflections', 20);
        foreach ($data as $row) {
            $item = $feed->createNewItem();
            static::addCommonToItemFromRow($item, $row, 'reflections');
            $feed->addItem($item);
        }

        return $feed->generateFeed();
    }

    protected static function addCommonToItemFromRow($item, $row, $type)
    {
        $link = getWebRoot() . '/' . $type . '/' . $row['url_title'];
        $item->setTitle($row['title']);
        $item->setDescription($row['body']);
        $item->setId($link, true);
        $item->setLink($link);
        $item->setDate(static::normalizeDate($row['date']));
    }

    protected static function addCommonToFeed($feed, $type)
    {
        $feed->setLink(getWebRoot() . '/' . $type);
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
        date_default_timezone_set(Config::get('default_timezone'));
        return $date;
    }

    protected static function getMediaSize($filename)
    {
        $path = __DIR__ . '/../public/media/audio/' . $filename;
        if (file_exists($path)) {
            return filesize($path);
        } else {
            return 0;
        }
    }

}

?>
