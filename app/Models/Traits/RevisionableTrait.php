<?php

namespace App\Models\Traits;

use Venturecraft\Revisionable\RevisionableTrait as VenturecraftRevisionableTrait;

/**
 * Provides Backpack Auth support to Venturcraft Revisionable.
 */
trait RevisionableTrait
{
    use VenturecraftRevisionableTrait {
        getSystemUserId as parentGetSystemUserId;
    }

    /**
     * Attempt to find the user id of the currently logged in user.
     *
     * Supports Cartalyst Sentry/Sentinel based authentication, Backpack Auth,
     * as well as stock Auth.
     *
     * @return int|null
     */
    public function getSystemUserId()
    {
        try {
            if (class_exists($class = '\SleepingOwl\AdminAuth\Facades\AdminAuth')
                || class_exists($class = '\Cartalyst\Sentry\Facades\Laravel\Sentry')
                || class_exists($class = '\Cartalyst\Sentinel\Laravel\Facades\Sentinel')
            ) {
                return ($class::check()) ? $class::getUser()->id : null;
            } elseif (backpack_auth()->check()) {
                return backpack_user()->getAuthIdentifier();
            } elseif (\Auth::check()) {
                return \Auth::user()->getAuthIdentifier();
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
}
