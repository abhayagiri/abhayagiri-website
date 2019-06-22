<?php

namespace App\YouTubeSync;

use App\Models\Author;
use Illuminate\Support\Collection;
use stdClass;

class Comparator
{
    /**
     * Extract a title and author from a YouTube video title.
     *
     * The video title should use the following convention:
     *
     *     <Talk.title_en> | <Author.title_en>
     *
     * Returns an object that has the following properties:
     *
     *     title:  the title
     *     author: the Author if identified or null if not determinable
     *     errors: a collection of string error messages
     *
     * @param  string  $title  the YouTube video title
     * @return \stdClass
     */
    public static function extractTitleAndAuthorFromYouTubeTitle(string $title) : stdClass
    {
        $parts = preg_split('/\s*\|\s*/', trim($title), 2);
        $result = new stdClass();
        $result->title = $parts[0];
        $result->author = null;
        $result->errors = new Collection;
        $name = trim($parts[1] ?? '');
        if ($name) {
            var_dump([$parts, $name]);
            $authors = Author::searchByMonkName($name);
            if ($authors->count() == 0) {
                $result->errors->add('Could not find any author from title: ' .
                                       var_export($title, true));
            } else if ($authors->count() == 1) {
                $result->author = $authors->first();
            } else {
                $result->errors->add('More than one author found from title: ' .
                                       var_export($title, true));
                $authors->each(function($author) use ($result) {
                    $result->errors->add('  ' . var_export($author->title_en, true) .
                                           ' (id=' . $author->id . ')');
                });
            }
        } else {
            $result->errors->add('Could not parse author from title: ' .
                                   var_export($title, true));
        }
        return $result;
    }
}
