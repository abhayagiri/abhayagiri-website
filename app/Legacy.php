<?php

namespace App;

use Illuminate\Http\Response;

class Legacy
{
    public static $REQUEST_KEYS = [
        '_page', '_subpage', '_subsubpage', '_entry', '_album', '_event',
        '_resident', 'url', 'sSearch', 'action', 'book', 'quantity', 'delete'
    ];

    public static $GLOBAL_VARIABLES = [
        '_action', '_page', '_page_title', '_subpage', '_subpage_title',
        '_subsubpage', '_subsubpage_title', '_meta_description', '_type',
        '_icon', 'order', 'url'
    ];

    public static function response($legacyPhpFile, $page)
    {
        static::setupRequestParams($page);
        ob_start();
        require base_path('legacy/' . $legacyPhpFile);
        $output = ob_get_clean();
        return new Response($output);
    }

    public static function setupRequestParams($page)
    {
        foreach (static::$REQUEST_KEYS as $key) {
            if (!array_key_exists($key, $_REQUEST)) {
                $_REQUEST[$key] = '';
                $_GET[$key] = '';
                $_POST[$key] = '';
            }
        }
        if ($page === false || $page === null) {
            return;
        }
        $parts = preg_split('/\\//', trim($page, '/'), 3);
        for ($i = 0; $i < 3; $i++) {
            $key = ['_page', '_subpage', '_subsubpage'][$i];
            $value = array_get($parts, $i, '');
            $_REQUEST[$key] = $_POST[$key] = $_GET[$key] = $value;
        }
    }
}
