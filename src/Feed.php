<?php

namespace Abhayagiri;

use FeedWriter\RSS2;

class Feed {

    public static function getAudioFeed()
    {
        $config = Config::getConfig();
        $func = Deprecated::getFunc();

        $feed = new RSS2;
        $feed->setTitle('Abhayagiri Audio');
        $feed->setLink($config['urlroot'] . '/audio');
        $feed->setDescription('Abhayagiri Dhamma Talks');
        $feed->setChannelElement('language', 'en-US');

        $data = $func->entry('audio', 100);
        foreach ($data as $row) {
            $item = $feed->createNewItem();
            $title = $row['title'];
            $date = static::normalizeDate($row['date']);
            $link = $config['urlroot'] . '/audio/' . $row['url_title'];
            $enclosureUrl = $config['urlroot'] . '/media/audio/' . $row['mp3'];
            $description = $row['body'];
            $enclosureSize = static::getMediaSize($row['mp3']);
            $item->setTitle($title);
            $item->setDescription($description);
            $item->setId($link, true);
            $item->setLink($link);
            $item->setDate($date);
            $item->setEnclosure($enclosureUrl, $enclosureSize, 'audio/mpeg');
            $feed->addItem($item);
        }

        return $feed->generateFeed();
    }

    public static function getNewsFeed()
    {
        $config = Config::getConfig();
        $func = Deprecated::getFunc();

        $feed = new RSS2;
        $feed->setTitle('Abhayagiri News');
        $feed->setLink($config['urlroot'] . '/news');
        $feed->setDescription('Abhayagiri News');
        $feed->setChannelElement('language', 'en-US');

        $data = $func->entry('news', 100);
        foreach ($data as $row) {
            $item = $feed->createNewItem();
            $title = $row['title'];
            $date = static::normalizeDate($row['date']);
            $link = $config['urlroot'] . '/news/' . $row['url_title'];
            $description = $row['body'];
            $item->setTitle($title);
            $item->setDescription($description);
            $item->setId($link, true);
            $item->setLink($link);
            $item->setDate($date);
            $feed->addItem($item);
        }

        return $feed->generateFeed();
    }


    public static function normalizeDate($date)
    {
        date_default_timezone_set('America/Los_Angeles');
        $date = strtotime($date);
        date_default_timezone_set(Config::getConfig()['default_timezone']);
        return $date;
    }

    public static function getMediaSize($filename)
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
