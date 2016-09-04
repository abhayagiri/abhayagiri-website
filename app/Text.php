<?php

namespace App;

class Text {

    private static $filter;

    public static function abridge($html, $length = 300)
    {
        $config = static::getConfig();
        $config->set('HTML.AllowedElements', 'a');
        $config->set('HTML.AllowedAttributes', 'a.href');
        $config->set('AutoFormat.RemoveSpansWithoutAttributes', false);
        $filter = new \HTMLPurifier($config);
        $html = $filter->purify($html);
        if (strlen($html) > $length) {
            $html = $filter->purify(substr($html, 0, $length) . '...');
        }
        return $html;
    }

    public static function cleanHTML($html)
    {
        if (static::$filter === null) {
            $config = static::getConfig();
            static::$filter = new \HTMLPurifier($config);
        }
        return static::$filter->purify($html);
    }

    public static function getConfig()
    {
        $tmpDir = storage_path('tmp/htmlpurifier');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8');
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $config->set('HTML.TidyLevel', 'heavy');
        $config->set('Cache.SerializerPath', $tmpDir);
        $config->set('AutoFormat.RemoveEmpty', true);
        $config->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
        $config->set('AutoFormat.RemoveSpansWithoutAttributes', true);
        $config->set('HTML.AllowedElements', 'h1,h2,h3,h4,h5,h6,p,span,blockquote,br,hr,strong,em,ul,ol,li,img,a');
        $config->set('HTML.AllowedAttributes', 'span.style,a.href,img.alt,img.src');
        return $config;
    }
}
