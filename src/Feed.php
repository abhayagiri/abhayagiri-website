<?php

namespace Abhayagiri;

use FeedWriter\RSS2;

class Feed {

    public static function getAudioFeed()
    {
        $func = Deprecated::getFunc();

        $feed = new RSS2;
        $feed->setTitle('Abhayagiri Audio');
        $feed->setLink(Config::get('urlroot') . '/audio');
        $feed->setDescription('Abhayagiri Dhamma Talks');
        $feed->setChannelElement('language', 'en-US');

        $data = $func->entry('audio', 100);
        foreach ($data as $row) {
            $item = $feed->createNewItem();
            static::addCommonToItemFromRow($item, $row, 'audio');
            $enclosureUrl = Config::get('urlroot') . '/media/audio/' . $row['mp3'];
            $enclosureSize = static::getMediaSize($row['mp3']);
            $item->setEnclosure($enclosureUrl, $enclosureSize, 'audio/mpeg');
            $feed->addItem($item);
        }

        return $feed->generateFeed();
    }

    public static function getNewsFeed()
    {
        $func = Deprecated::getFunc();

        $feed = new RSS2;
        $feed->setTitle('Abhayagiri News');
        $feed->setLink(Config::get('urlroot') . '/news');
        $feed->setDescription('Abhayagiri News');
        $feed->setChannelElement('language', 'en-US');

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
        $func = Deprecated::getFunc();

        $feed = new RSS2;
        $feed->setTitle('Abhayagiri Reflections');
        $feed->setLink(Config::get('urlroot') . '/reflections');
        $feed->setDescription('Abhayagiri Reflections');
        $feed->setChannelElement('language', 'en-US');

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
        $title = $row['title'];
        $date = static::normalizeDate($row['date']);
        $link = Config::get('urlroot') . '/' . $type . '/' . $row['url_title'];
        $description = $row['body'];
        $item->setTitle($title);
        $item->setDescription($description);
        $item->setId($link, true);
        $item->setLink($link);
        $item->setDate($date);
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
