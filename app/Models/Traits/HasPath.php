<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Lang;

/**
 * This provides models with a localized path attribute.
 */
trait HasPath
{
    /**
     * Return the path for this model's show route.
     *
     * @param  string|null  $lng
     *
     * @return string|null
     */
    public function getPath(?string $lng = null): ?string
    {
        $routeId = $this->getRouteId();
        if ($routeId === null) {
            return null;
        }
        if ($lng === null) {
            $lng = Lang::locale();
        }
        $routePrefix = $lng === 'th' ? 'th.' : '';
        return route($routePrefix . $this->getRouteName(), $routeId, false);
    }

    /**
     * The accessor for getPath().
     *
     * @return string
     */
    public function getPathAttribute(): string
    {
        return $this->getPath(Lang::locale());
    }

    /**
     * Return the URL for this model's show route.
     *
     * @param  string|null  $lng
     *
     * @return string|null
     */
    public function getUrl(?string $lng = null): ?string
    {
        $path = $this->getPath($lng);
        if ($path !== null) {
            return url($path);
        } else {
            return null;
        }
    }

    /**
     * The accessor for getUrl().
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return $this->getUrl(Lang::locale());
    }

    /**
     * Return the router ID.
     *
     * @return string|null
     */
    protected function getRouteId(): ?string
    {
        $id = $this->getKey();
        if ($id !== null) {
            $slug = $this->getAttribute('slug');
            return ((string) $id) . ($slug !== null ? ('-' . urlencode($slug)) : '');
        } else {
            return null;
        }
    }

    /**
     * Return the name for the show route.
     *
     * @return string
     */
    protected function getRouteName(): string
    {
        return $this->getTable() . '.show';
    }
}
