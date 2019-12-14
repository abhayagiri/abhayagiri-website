<?php

namespace App\Legacy;

use DB as LDB;

class Func {

    protected $_db;

    public $language;
    public $base;

    public function __construct($_language = 'English') {
        $this->_db = DB::getDB();
        $this->language = $_language;
        if ($this->language == 'Thai') {
            $this->base = 'th';
        } else {
            $this->base = '';
        }
    }

    /* ------------------------------------------------------------------------------
      Entry
      ------------------------------------------------------------------------------ */

    public function entry($table, $limit = 1) {
        return $this->_db->_query("SELECT * FROM $table WHERE (status='Open') AND (date < NOW()) AND (language LIKE '%{$this->language}%') ORDER BY date DESC LIMIT $limit");
    }

    /* ------------------------------------------------------------------------------
      Icon
      ------------------------------------------------------------------------------ */

    public function page($table) {
        $pagesJson = file_get_contents(base_path('new/data/pages.json'));
        $pages = json_decode($pagesJson, true);
        $page = null;
        foreach ($pages as $p) {
            if ($p['slug'] === $table) {
                $page = $p;
                break;
            }
        }
        if (!$page) {
            $page = $pages[0];
        }
        return [
            'thai_title' => $page['titleTh'],
            'display_type' => $page['displayType'],
            'icon' => $page['oldIcon'],
        ];
    }

    /* ------------------------------------------------------------------------------
      book
      ------------------------------------------------------------------------------ */

    public function book($id) {
        $stmt = $this->_db->_query("SELECT * FROM books WHERE id=$id");
        return $stmt[0];
    }

    /* ------------------------------------------------------------------------------
      Authors
      ------------------------------------------------------------------------------ */

    public function authors() {
        $array = \App\Models\Author::all()->toArray();
        foreach ($array as &$item) {
            $item['title'] = $item['title_en'];
        }
        return $array;
    }

    /* ------------------------------------------------------------------------------
      Subpage
      ------------------------------------------------------------------------------ */

    public function subpage($_subpage) {
        return $row = $this->_db->_select('subpages', 'source,body,title', array("url_title" => $_subpage, "language" => "{$this->language}", "status" => "Open"));
    }

    /* ------------------------------------------------------------------------------
      Submenu
      ------------------------------------------------------------------------------ */

    public function submenu($_page) {
        return $this->_db->_select('subpages', 'title,url_title', array("page" => $_page, "language" => "{$this->language}", "status" => "Open"), '');
    }

    /* ------------------------------------------------------------------------------
      Display Date/Time
      ------------------------------------------------------------------------------ */

    public function display_date($date) {
        return date("F j, Y", strtotime($date));
    }

    public function display_time($time) {
        return date("g:i a", strtotime($date));
    }

    /* ------------------------------------------------------------------------------
      Title Case
      ------------------------------------------------------------------------------ */

    public function title_case($title) {
        $title = str_replace('-', ' ', $title);
        $smallwordsarray = array(
            'of', 'the', 'and'
        );
        $words = explode(' ', $title);
        foreach ($words as $key => $word) {
            if ($key != 0 && in_array($word, $smallwordsarray))
                $words[$key] = strtolower($word);
            else
                $words[$key] = ucfirst($word);
        }
        $title = implode(' ', $words);
        return $title;
    }

    public function searchLength($val) {
        return (strlen($val) > 300) ? mb_substr($val, 0, 300, 'UTF-8') . "..." : $val;
    }

    public function highlight($needle, $haystack) {
        $ind = stripos($haystack, $needle);
        $len = strlen($needle);
        if ($ind !== false) {
            return substr($haystack, 0, $ind) . "<span class = 'match'>" . substr($haystack, $ind, $len) . '</span>' .
                    $this->highlight($needle, substr($haystack, $ind + $len));
        }
        else
            return $haystack;
    }

    public function fixLength($val, $length = '600') {
        if (strlen($val) >= $length) {
            ini_set("display_errors", 0);
            $doc = new \DOMDocument();
            $html = mb_substr($val, 0, $length, 'UTF-8') . "...";
            @$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
            $html = $doc->saveHTML();
            return $html;
        }
        else
            return $val;
    }

    public function stripDiacritics($string) {
        $table = array(
            'Ā' => 'A', 'ā' => 'a', 'Ī' => 'I', 'ī' => 'i', 'Ū' => 'U', 'ū' => 'u', 'Ṅ' => 'N',
            'ṅ' => 'n', 'Ñ' => 'N', 'ñ' => 'n', 'Ṭ' => 'T', 'ṭ' => 't', 'Ḍ' => 'D', 'ḍ' => 'd',
            'Ṇ' => 'N', 'ṇ' => 'n', 'Ḷ' => 'L', 'ḷ' => 'l', 'Ṁ' => 'M', 'ṁ' => 'm'
        );
        return strtr($string, $table);
    }

    public function searchable($col, $query) {
        $query = explode(' ', $query);
        $query = implode(" %' OR $col LIKE '% ", $query);
        $query = "title LIKE '% $query %'";
        return $query;
    }

    public function google_calendar($feed = null) { return false;
        if (!$feed) {
            $startmin = date("Y-m-d", strtotime("today")); // This sets it to the previous monday
            $startmax = date("Y-m-d", strtotime($startmin . " + 31 days"));
            $feed = "?singleevents=true&orderby=starttime&sortorder=a&start-min=" . $startmin . "&start-max=" . $startmax . "&max-results=4";
        }
        $feed = 'https://www.google.com/calendar/feeds/abhayagiri.org_2tr1cpnhbe4i5cria1l6ae8si8%40group.calendar.google.com/public/full/' . $feed;
        return simplexml_load_file($feed);
    }

    function google_picasa_images($album, $thumb_max_size = "200u") {
        $user = "110976577577357155764";
        $feed_url = "https://picasaweb.google.com/data/feed/base/user/$user/albumid/$album?imgmax=1200&thumbsize=174";
        $xml = new \DOMDocument();
        $xml->load($feed_url);
        $namespace_media = $xml->getElementsByTagName('feed')->item(0)->getAttribute('xmlns:media');

        $pictures = array();
        foreach ($xml->getElementsByTagName('entry') as $entry) {
            $elem = $entry->getElementsByTagNameNS($namespace_media, 'group')->item(0);
            $thumb = array('url' => '', 'size' => 0);
            foreach ($elem->getElementsByTagNameNS($namespace_media, 'thumbnail') as $xml_thumb) {
                $thumb_size = (int) $xml_thumb->getAttribute('height');
                $thumb_width = (int) $xml_thumb->getAttribute('width');
                if ($thumb_width < $thumb_size)
                    $thumb_size = $thumb_width;
                if ($thumb_size < $thumb_max_size && $thumb_size > $thumb['size']) {
                    $thumb['url'] = $xml_thumb->getAttribute('url');
                    $thumb['size'] = $thumb_size;
                }
            }
            $content_tag = $elem->getElementsByTagNameNS($namespace_media, 'content')->item(0);
            $picture = array(
                'title' => $elem->getElementsByTagNameNS($namespace_media, 'title')->item(0)->nodeValue,
                'thumbnail' => $thumb['url'],
                'url' => $content_tag->getAttribute('url'),
                'width' => $content_tag->getAttribute('width'),
                'height' => $content_tag->getAttribute('height'),
                'description' => $elem->getElementsByTagNameNS($namespace_media, 'description')->item(0)->nodeValue,
                'mimetype' => $content_tag->getAttribute('type'),
            );
            $keywords = $elem->getElementsByTagNameNS($namespace_media, 'keywords')->item(0)->nodeValue;
            if (isset($keywords{0}))
                foreach (explode(',', $keywords) as $keyword)
                    $picture['keywords'][] = trim($keyword);
            $pictures [] = $picture;
        }

        return $pictures;
    }

    function google_picasa_albums($thumb_max_size = 320) {
        $user = "110976577577357155764";
        $feed_url = "https://picasaweb.google.com/data/feed/api/user/$user?imgmax=320";
        $xml = new \DOMDocument();
        $xml->load($feed_url);
        $namespace_media = $xml->getElementsByTagName('feed')->item(0)->getAttribute('xmlns:media');
        $namespace_gphoto = $xml->getElementsByTagName('feed')->item(0)->getAttribute('xmlns:gphoto');
        $pictures = array();
        foreach ($xml->getElementsByTagName('entry') as $entry) {
            $elem = $entry->getElementsByTagNameNS($namespace_media, 'group')->item(0);
            $thumb = array('url' => '', 'size' => 0);
            foreach ($elem->getElementsByTagNameNS($namespace_media, 'thumbnail') as $xml_thumb) {
                $thumb_size = (int) $xml_thumb->getAttribute('height');
                $thumb_width = (int) $xml_thumb->getAttribute('width');
                if ($thumb_width < $thumb_size)
                    $thumb_size = $thumb_width;
                if ($thumb_size < $thumb_max_size && $thumb_size > $thumb['size']) {
                    $thumb['url'] = $xml_thumb->getAttribute('url');
                    $thumb['size'] = "320";
                }
            }
            $content_tag = $elem->getElementsByTagNameNS($namespace_media, 'content')->item(0);
            $picture = array(
                'id' => $entry->getElementsByTagNameNS($namespace_gphoto, 'id')->item(0)->nodeValue,
                'title' => $elem->getElementsByTagNameNS($namespace_media, 'title')->item(0)->nodeValue,
                'description' => $elem->getElementsByTagNameNS($namespace_media, 'description')->item(0)->nodeValue,
                'thumbnail' => $thumb['url'],
                'url' => $content_tag->getAttribute('url'),
                'mimetype' => $content_tag->getAttribute('type')
            );
            $keywords = $elem->getElementsByTagNameNS($namespace_media, 'keywords')->item(0)->nodeValue;
            if (isset($keywords{0}))
                foreach (explode(',', $keywords) as $keyword)
                    $picture['keywords'][] = trim($keyword);
            $pictures [] = $picture;
        }

        return $pictures;
    }

    function google_picasa_album_data($album) {
        $user = "110976577577357155764";
        $feed_url = "https://picasaweb.google.com/data/feed/base/user/$user/albumid/$album";
        $xml = new \DOMDocument();
        $xml->load($feed_url);
        return $xml->getElementsByTagName('title')->item(0)->nodeValue;
    }

    function hostedAlbums()
    {
        $url = 'http://gallery.abhayagiri.org/ws.php?format=json&method=pwg.categories.getList&public=false&thumbnail_size=xsmall';
        $result = [];
        $json = file_get_contents($url);
        $obj = json_decode($json);
        if ($obj && $obj->stat === 'ok') {
            foreach ($obj->result->categories as $category) {
                $result[] = [
                    'id' => '-' . $category->id,
                    'title' => $category->name,
                    'description' => $category->comment,
                    'url' => $category->tn_url,
                ];
            }
        }
        return $result;
    }

    function galleryAlbums()
    {
        return array_merge(
            $this->hostedAlbums(),
            $this->google_picasa_albums()
        );
    }

    function hostedAlbumTitle($id)
    {
        $url = 'http://gallery.abhayagiri.org/ws.php?format=json&method=pwg.categories.getList&public=false&thumbnail_size=xsmall&cat_id=' . $id;
        $result = [];
        $json = file_get_contents($url);
        $obj = json_decode($json);
        if ($obj && $obj->stat === 'ok') {
            return $obj->result->categories[0]->name;
        } else {
            return 'Not found';
        }
    }

    function galleryAlbumTitle($id)
    {
        if (preg_match('/^-(\d+)$/', $id, $matches)) {
            return $this->hostedAlbumTitle($matches[1]);
        } else {
            return $this->google_picasa_album_data($id);
        }
    }

    function hostedImages($id)
    {
        $url = 'http://gallery.abhayagiri.org/ws.php?format=json&method=pwg.categories.getImages&per_page=500&cat_id=' . $id;
        $json = file_get_contents($url);
        $obj = json_decode($json);
        if ($obj && $obj->stat === 'ok') {
            foreach ($obj->result->images as $image) {
                $result[] = [
                    'description' => $image->name,
                    'thumbnail' => $image->derivatives->small->url,
                    'width' => $image->derivatives->xlarge->width,
                    'height' => $image->derivatives->xlarge->height,
                    'url' => $image->derivatives->xlarge->url,
                ];
            }
        }
        return $result;
    }

    function galleryImages($id)
    {
        if (preg_match('/^-(\d+)$/', $id, $matches)) {
            return $this->hostedImages($matches[1]);
        } else {
            return $this->google_picasa_images($id);
        }
    }
}
