<?php

namespace App;

use FeedWriter\RSS2;

use App\Legacy\Func;

class Feed
{
    public static function getAudioFeed()
    {
        $func = new Func();

        $feed = new RSS2;
        $feed->setTitle('Abhayagiri Audio');
        $feed->setDescription('Abhayagiri Dhamma Talks');
        static::addCommonToFeed($feed, 'audio');

        $talks = \App\Models\Talk::where('type_id', 2)
            ->public()->latestVisible()->latest()
            ->with('author')
            ->limit(100);

        $talks->get()->each(function($talk) use ($feed) {
            $row = [
                'author' => $talk->author->title_en,
                'author_image_path' => $talk->author->image_path ?
                    '/media/' . $talk->author->image_path : null,
                'title' => $talk->title,
                'link' => $talk->getPath(),
                'body' => $talk->body,
                'date' => $talk->posted_at,
                'mp3' => $talk->mp3,
            ];
            $item = $feed->createNewItem();
            static::addCommonToItemFromRow($item, $row, 'audio');
            $enclosureUrl = \URL::to('/media/audio/' . $row['mp3']);
            $enclosureSize = static::getMediaSize($row['mp3']);
            $item->addEnclosure($enclosureUrl, $enclosureSize, 'audio/mpeg');
            $feed->addItem($item);
        });

        return $feed->generateFeed();
    }

    public static function getNewsFeed()
    {
        $func = new Func();

        $feed = new RSS2;
        $feed->setTitle('Abhayagiri News');
        $feed->setDescription('Abhayagiri News');
        static::addCommonToFeed($feed, 'news');

        $newss = \App\Models\News::public()->latest()
            ->limit(100)->get();

        foreach ($newss as $news) {
            $row = $news->toLegacyArray();
            $row['link'] = $news->getPath();
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
        if (array_get($row, 'link')) {
            $link = \URL::to($row['link']);
        } else {
            $link = \URL::to('/' . $type . '/' . $row['url_title']);
        }
        $item->setTitle($row['title']);
        $item->setDescription($row['body']);
        $item->setId($link, true);
        $item->setLink($link);
        $item->setDate(static::normalizeDate($row['date']));
        if (array_key_exists('author', $row)) {
            $item->setAuthor($row['author']);
            $item->addElement('dc:creator', $row['author']);
            if (array_get($row, 'author_image_path')) {
                $imageURL = \URL::to($row['author_image_path']);
            } else {
                $func = new Func();
                $imageURL = \URL::to($func->getAuthorImagePath($row['author']));
            }
            $item->addElement('media:content', null, [
                'url' => $imageURL,
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
