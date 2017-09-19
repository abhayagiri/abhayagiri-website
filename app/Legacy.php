<?php

namespace App;

use Illuminate\Support\Facades\Config;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Backpack\Settings\app\Models\Setting;

use Legacy\RedirectException;

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
        static::overrideRequestParamsWithLaravelParams();
        static::setupSettings();
        ob_start();
        try {
            require base_path('legacy/' . $legacyPhpFile);
        # !!!??? For some reason we need to do this instead of:
        # catch (RedirectException $e) {
        } catch (\Exception $e) {
            ob_get_clean();
            if (is_a($e, 'App\\Legacy\\RedirectException')) {
                return Redirect::to($e->url);
            } else {
                throw $e;
            }
        }
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

    public static function overrideRequestParamsWithLaravelParams()
    {
        $postMethod = Request::isMethod('post');
        foreach (Input::all() as $key => $value) {
            if ($postMethod) {
                $_POST[$key] = $value;
            } else {
                $_GET[$key] = $value;
            }
            $_REQUEST[$key] = $value;
        }
    }

    /**
     * For some reason, settings are getting lost on these legacy routes.
     *
     * We manually inject these by copying the boot code.
     *
     * @see https://github.com/Laravel-Backpack/Settings/blob/master/src/SettingsServiceProvider.php#L33
     */
    public static function setupSettings()
    {
        $settings = Setting::all();
        foreach ($settings as $key => $setting) {
            \Config::set('settings.'.$setting->key, $setting->value);
        }
    }

    /*
     * Datatable methods
     */

    public static function getDatatables($get, $totalQuery, $totalDisplayQuery, $dataQuery)
    {
        $dataQuery = $dataQuery
            ->limit((int) array_get($get, 'iDisplayLength'))
            ->offset((int) array_get($get, 'iDisplayStart'));
        return [$dataQuery->get(), [
            'sEcho' => (int) array_get($get, 'sEcho'),
            'iTotalRecords' => $totalQuery->count(),
            'iTotalDisplayRecords' => $totalDisplayQuery->count(),
            'aaData' => [],
        ]];
    }

    public static function scopeDatatablesSearch($get, $query, $columns)
    {
        $searchText = array_get($get, 'sSearch', '');
        if ($searchText !== '') {
            $likeQuery = '%' . Util::escapeLikeQueryText($searchText) . '%';
            $query = $query->where(function ($query)
                    use ($likeQuery, $searchText, $columns) {
                $query->where('id', '=', '$searchText');
                foreach ($columns as $column) {
                    $query->orWhere($column, 'LIKE', $likeQuery);
                }
            });
        }
        return $query;
    }

    public static function getEnglishOrThai($english, $thai, $language = null)
    {
        if ($language === 'Thai' || Lang::locale() === 'th') {
            return $thai ? $thai : $english;
        } else {
            return $english;
        }
    }

    public static function getAuthor($author, $language)
    {
        return static::getEnglishOrThai(
            $author->title_en, $author->title_th, $language);
    }

    public static function getTitleWithAlt($model, $language)
    {
        $title = $model->title;
        if ($language === 'Thai') {
            $alt = $model->alt_title_th;
        } else {
            $alt = $model->alt_title_en;
        }
        if ($alt) {
            $title .= ' (' . $alt . ')';
        }
        return $title;
    }
}
