<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class NavMenuComposer
{
    /**
     * Create a new page composer.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Bind data to the view.
     *
     * @param  Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('navMenu', $this->getNavMenu());
    }

    /**
     * Returns an array of objects of navigation menu items with the form:
     *
     *   path: path for link
     *   title: localized title
     *   type: 'legacy' or 'new'
     *   icon: Font Awesome CSS class
     *   active: whether or not the current path belongs to the menu item
     *
     * @param string $path (optional) the current request path
     * @param string $lng (optional) the language to localize title
     * @return array
     */
    public function getNavMenu(string $path = null, string $lng = null)
                               : array
    {
        $path = $path ?: Request::path();
        $lng = $lng ?: Lang::locale();
        // TODO move the data in pages.json to config/pages.php
        $json = file_get_contents(base_path('new/data/pages.json'));
        $data = json_decode($json);
        foreach ($data as &$item) {
            $item->title = $lng === 'th' ? $item->titleTh : $item->titleEn;
            if ($path === '/th') {
                $path = '/';
            } else if (Str::startsWith($path, '/th/')) {
                $path = substr($path, 3);
            }
            $path = rtrim($path, '/');
            if ($path === '' || $path === '/home' ) {
                $item->active = $item->path === '/';
            } else {
                if ($path === $item->path) {
                    $item->active = true;
                } else {
                    $item->active = Str::startsWith($path, $item->path . '/');
                }
            }
        }
        return $data;
    }
}
