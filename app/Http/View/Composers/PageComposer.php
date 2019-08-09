<?php

namespace App\Http\View\Composers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageComposer
{
    /**
     * The cached pages.
     *
     * @var Illuminate\Support\Collection|null
     */
    protected static $pages = null;

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
     * @param Illuminate\View\View $view
     * @return void
     */
    public function compose(View $view)
    {
        $path = Request::path();
        $view->with('pageMenu', $this->getPageMenu($path))
             ->with('pageSlug', $this->getPageSlug($path));
    }

    /**
     * Return an array of page menu item objects with the form:
     *
     *   slug: short name
     *   path: path for links
     *   title: localized title
     *   type: 'legacy' or 'new'
     *   icon: Font Awesome CSS class
     *   active: whether or not the current path belongs to the menu item
     *
     * @param string $path the request path
     * @return Illuminate\Support\Collection
     */
    public function getPageMenu(string $path) : Collection
    {
        if ($path === '/th' || Str::startsWith($path, '/th/')) {
            $lng = 'th';
        } else {
            $lng = 'en';
        }
        $slug = $this->getPageSlug($path);
        $hasActive = false;
        $menu = $this->getPages()->mapWithKeys(function ($item)
                                               use ($lng, $slug, &$hasActive) {
            $item = clone $item;
            $item->title = $lng === 'th' ? $item->titleTh : $item->titleEn;
            $item->active = $slug === $item->slug;
            $hasActive = $hasActive || $item->active;
            return [$item->slug => $item];
        });
        if (!$hasActive) {
            $menu[0]->active = true;
        }
        return $menu;
    }

    /**
     * Return the page slug for the given request path.
     *
     * @param string $path the request path
     * @return string
     */
    public function getPageSlug(string $path) : string
    {
        $parts = preg_split('_/_', trim($path, '/'));
        if ($parts[0] === 'th') {
            $parts = array_slice($parts, 1);
        }
        $slug = $parts[0] ?? '';
        if ($slug === 'subpages') {
            // TODO should query model, return page attribute
            return 'home';
        } else if ($this->getPages()->has($slug)) {
            return $slug;
        } else {
            return 'home';
        }
    }

    /**
     * Return (and cache) pages.
     *
     * @return Illuminate\Support\Collection
     */
    public function getPages() : Collection
    {
        $pages = static::$pages;
        if (!$pages) {
            // TODO move the data in pages.json to config/pages.php
            $json = file_get_contents(base_path('new/data/pages.json'));
            $pages = new Collection(json_decode($json));
            static::$pages = $pages = $pages->mapWithKeys(function ($page) {
                return [$page->slug => $page];
            });
        }
        return $pages;
    }
}
