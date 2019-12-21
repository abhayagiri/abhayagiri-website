<?php

namespace App\Http\View;

use Illuminate\Support\Collection;
use App\Facades\Pages;
use stdClass;

class Breadcrumbs extends Collection
{
    /**
     * Add a breadcrumb. Each breadcrumb is an object with the following
     * attributes:
     *
     * - title: the title
     * - path: the path (or null)
     * - last: whether or not this is the last breadcrumb
     *
     * @param  string  $title
     * @param  string  $path
     * @return \App\Utilities\Breadcrumbs
     */
    public function addBreadcrumb(string $title, ?string $path = null): Breadcrumbs
    {
        $breadcrumb = new stdClass();
        $breadcrumb->title = $title;
        $breadcrumb->path = $path;
        $breadcrumb->link = false;
        $breadcrumb->last = true;
        if ($this->isNotEmpty()) {
            $last = $this[$this->count() - 1];
            $last->link = !is_null($last->path);
            $last->last = false;
        }
        $this->push($breadcrumb);
        return $this;
    }

    /**
     * Add the breadcrumb(s) for the current page.
     *
     * @return \App\Utilities\Breadcrumbs
     */
    public function addPageBreadcrumbs(): Breadcrumbs
    {
        $existing = $this->items;
        $this->items = [];
        $this->addBreadcrumb(__('common.home'), lp('/'));
        $page = $this->currentPage();
        if ($page->slug !== 'home') {
            $this->addBreadcrumb($page->title, lp($page->path));
        }
        foreach ($existing as $breadcrumb) {
            $this->addBreadcrumb($breadcrumb->title, $breadcrumb->path);
        }
        return $this;
    }

    /**
     * Return the page for the current request.
     *
     * @see \App\Http\View\Pages::current()
     *
     * @return stdClass
     */
    public function currentPage(): stdClass
    {
        return Pages::current();
    }
}
