<?php

namespace App\Http\View;

use App\Models\Subpage;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use stdClass;

class Pages
{
    /**
     * The pages data.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $pages;

    /**
     * Create a new pages instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->pages = $this->loadPages();
    }

    /**
     * Return a collection fo all the pages. Each page is an object as described
     * in current() with the following additional attribute:
     *
     * - current: whether or not the current request belongs to the page
     *
     * @see current()
     *
     * @return \Illuminate\Support\Collection
     */
    public function all(): Collection
    {
        $lng = $this->lng();
        $slug = $this->slug();
        $hasActive = false;
        $result = $this->pages->mapWithKeys(function ($item) use ($lng, $slug, &$hasActive) {
            $item = clone $item;
            $item->title = $lng === 'th' ? $item->titleTh : $item->titleEn;
            // TODO remove $this->active
            $item->active = $item->current = $slug === $item->slug;
            $hasActive = $hasActive || $item->active;
            return [$item->slug => $item];
        });
        if (!$hasActive) {
            $result[0]->active = true;
        }
        return $result;
    }

    /**
     * Return the active page for the current request. This object has the
     * following attributes:
     *
     * - slug: short name
     * - path: path for links
     * - title: localized title
     * - type: 'legacy' or 'new'
     * - icon: Font Awesome CSS class
     *
     * @return stdClass
     */
    public function current(): stdClass
    {
        return $this->all()->firstWhere('active', true);
    }

    /**
     * Return the page identified by slug, or the home page if not found. The
     * returned page is an object as described in current().
     *
     * @see current()
     *
     * @return stdClass
     */
    public function get(string $slug): stdClass
    {
        $all = $this->all();
        return $all->get($slug, $all->first());
    }

    /**
     * Return the page language for the current request.
     *
     * @return string
     */
    public function lng(): string
    {
        $path = trim($this->path(), '/');
        if ($path === 'th' || Str::startsWith($path, 'th/')) {
            return 'th';
        } else {
            return 'en';
        }
    }

    /**
     * Return the path for the current request.
     *
     * @param  string  $path
     * @return string
     */
    public function path(): string
    {
        return Request::path();
    }

    /**
     * Return an object with the following properties;
     *
     *   lng: the other language key ('en' or 'th')
     *   cssFlag: the other language CSS flag ('flag-us' or 'flag-th')
     *   transkey: the translation lookup key ('english', 'thai')
     *
     * @return stdClass
     */
    public function otherLngData(): stdClass
    {
        $lng = $this->lng();
        $result = new stdClass;
        if ($lng === 'th') {
            $result->lng = 'en';
            $result->cssFlag = 'flag-us';
            $result->transKey = 'english';
        } else {
            $result->lng = 'th';
            $result->cssFlag = 'flag-th';
            $result->transKey = 'thai';
        }
        return $result;
    }

    /**
     * Return the page slug for the current request.
     *
     * @param  string  $path
     * @return string
     */
    public function slug()
    {
        $parts = preg_split('_/_', trim($this->path(), '/'));
        if ($parts[0] === 'th') {
            $parts = array_slice($parts, 1);
        }
        $slug = $parts[0] ?? '';
        // Handle /subpages/*
        if ($slug === 'subpages') {
            $subpage = Subpage::find($parts[1]);
            $slug = $subpage->page ?? '';
        }
        if ($this->pages->has($slug)) {
            return $slug;
        } else {
            return 'home';
        }
    }

    /**
     * Load the pages data from file.
     *
     * @return Illuminate\Support\Collection
     */
    protected function loadPages(): Collection
    {
        // TODO move the data in pages.json to config/pages.php
        $json = file_get_contents(base_path('new/data/pages.json'));
        return collect(json_decode($json))->mapWithKeys(function ($page) {
            return [$page->slug => $page];
        });
    }
}
