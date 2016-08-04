<?php

namespace Abhayagiri;

class Func {

    protected $_db;

    public function __construct($_language = 'English') {
        $this->_db = DB::getDB();
        $this->language = $_language;
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
        $stmt = $this->_db->_query("SELECT thai_title,display_type,icon FROM pages WHERE url_title='$table'");
        return $stmt[0];
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
        return $this->_db->_select('authors', 'title');
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
      Abridge
      ------------------------------------------------------------------------------ */

    public function abridge($article, $length = 300) {
        if (strlen($article) < $length) {
            return $article;
        } else {
            return (substr(strip_tags($article), 0, $length) . "...");
        }
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
            $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
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

    // Mahapanel

    public function addColumn($table, $column, $type) {
        $type = $this->getType($type);
        $stmt = $this->_db->_alter('add', $table, $column, $type);
    }

    public function deleteColumn($table, $column) {
        $stmt = $this->_db->_alter('drop', $table, $column);
    }

    public function updateColumn($table, $column, $type, $column2) {
        $type = $this->getType($type);
        $stmt = $this->_db->_alter('change', $table, $column, $type, $column2);
    }

    public function addPage($page, $user) {
        $stmt = $this->_db->_create($page);
        $id = $this->getTableId($page);
        $this->addColumn($page, 'title', 'text');
        $this->addColumn($page, 'url_title', 'text');
        $this->addColumn($page, 'user', 'user');
        $this->addColumn($page, 'date', 'date');
        $this->addColumn($page, 'status', 'dropdown');
        $stmt = $this->_db->_insert('columns', array("title" => 'title', 'display_title' => 'Title', 'date' => date("Y-m-d H:i:s"), 'column_type' => 'title', 'user' => $user,
            'display' => 'yes', 'parent' => $id));
        $stmt = $this->_db->_insert('columns', array("title" => 'url_title', 'display_title' => 'URL Title', 'date' => date("Y-m-d H:i:s"), 'column_type' => 'text', 'user' => $user,
            'display' => 'yes', 'parent' => $id));
        $stmt = $this->_db->_insert('columns', array("title" => 'user', 'display_title' => 'Created By', 'date' => date("Y-m-d H:i:s"), 'column_type' => 'user', 'user' => $user,
            'display' => 'yes', 'parent' => $id));
        $stmt = $this->_db->_insert('columns', array("title" => 'date', 'display_title' => 'Date', 'date' => date("Y-m-d H:i:s"), 'column_type' => 'date', 'user' => $user,
            'display' => 'yes', 'parent' => $id));
        $stmt = $this->_db->_insert('columns', array("title" => 'status', 'display_title' => 'Status', 'date' => date("Y-m-d H:i:s"), 'column_type' => 'dropdown', 'user' => $user,
            'display' => 'yes', 'parent' => $id));
    }

    public function deletePage($id, $page) {
        $stmt = $this->_db->_drop($page);
        $stmt = $this->_db->_delete('columns', array('parent' => $id));
    }

    public function updatePage($old_title, $new_title) {
        $stmt = $this->_db->_rename($old_title, $new_title);
    }

    public function getType($type) {
        switch ($type) {
            case "textarea":
                $type = "mediumtext";
                break;
            case "date":
                $type = "datetime";
                break;
            case "user":
            case "id":
                $type = "int(5)";
                break;
            default:
                $type = "varchar(300)";
                break;
        }
        return $type;
    }

    public function getTableName($id) {
        $stmt = $this->_db->_select("pages", "url_title", array("id" => $id));
        return $stmt[0]['url_title'];
    }

    public function getColumnName($id) {
        $stmt = $this->_db->_select("columns", "title", array("id" => $id));
        return $stmt[0]['title'];
    }

    public function getTableId($url_title) {
        $stmt = $this->_db->_select("pages", "id", array("url_title" => $url_title));
        return $stmt[0]['id'];
    }

    public function getAuthorImagePath($author) {
        $normalizedAuthor = strtolower(
            $this->stripDiacritics(str_replace(' ', '_', $author)));
        $path = "/media/images/speakers/speakers_$normalizedAuthor.jpg";
        if (!file_exists(getPublicDir() . $path)) {
            $path = '/media/images/speakers/speakers_abhayagiri_sangha.jpg';
        }
        return $path;
    }

}

?>
