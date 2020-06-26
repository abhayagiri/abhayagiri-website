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
     * @param  bool  $withSlug
     *
     * @return string|null
     */
    public function getPath(?string $lng = null, bool $withSlug = true): ?string
    {
        $routeId = $this->getRouteId($withSlug);
        if ($routeId === null) {
            return null;
        }
        if ($lng === null) {
            $lng = Lang::locale();
        }
        $routePrefix = $lng === 'th' ? 'th.' : '';
        return route($routePrefix . $this->getRouteName() . '.show', $routeId, false);
    }

    /**
     * The accessor for getPath().
     *
     * @return string
     */
    public function getPathAttribute(): string
    {
        return $this->getPath(Lang::locale(), true);
    }

    /**
     * Return the URL for this model's show route.
     *
     * @param  string|null  $lng
     * @param  bool  $withSlug
     * @return string|null
     */
    public function getUrl(?string $lng = null, bool $withSlug = true): ?string
    {
        $path = $this->getPath($lng, $withSlug);
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
        return $this->getUrl(Lang::locale(), true);
    }

    /**
     * Return the router ID.
     *
     * @param  bool  $withSlug
     * @return string|null
     */
    protected function getRouteId($withSlug = true): ?string
    {
        $id = $this->getKey();
        if ($id !== null) {
            $slug = $withSlug ? $this->getAttribute('slug') : null;
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
        return $this->getTable();
    }
}
