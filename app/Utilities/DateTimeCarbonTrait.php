<?php

namespace App\Utilities;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

/**
 * A Carbon mixin trait to support the most common date / time getters used by
 * the application.
 *
 * @see \App\Providers\DateTimeServiceProvider
 * @see https://carbon.nesbot.com/docs/#api-macro
 */
trait DateTimeCarbonTrait
{
    /**
     * The ISO format string for a date.
     *
     * @see formatUserDate()
     * @var string
     */
    protected $userFormatDate = 'LL';

    /**
     * The ISO format string for a date and time.
     *
     * @see formatUserDateTime()
     * @var string
     */
    protected $userFormatDateTime = 'LLL';

    /**
     * Return a Carbon instance with timezone and locale set for the current
     * user / request.
     *
     * @return \Carbon\Carbon
     */
    public function forUser(): Carbon
    {
        return $this->copy()
                    ->tz($this->getUserTimezone())
                    ->locale($this->getUserLocale());
    }

    /**
     * Return the canonical date string for the current user / request.
     *
     * @return string
     */
    public function formatUserDate(): string
    {
        return $this->forUser()->isoFormat($this->userFormatDate);
    }

    /**
     * Return the canonical date HTML for the current user / request.
     *
     * @return string
     */
    public function formatUserDateHtml(): string
    {
        return '<time datetime="' . e($this->toISOString()) . '">' .
               e($this->formatUserDate()) . '</time>';
    }

    /**
     * Return the canonical date / time string for the current user / request.
     *
     * @return string
     */
    public function formatUserDateTime(): string
    {
        return $this->forUser()->isoFormat($this->userFormatDateTime);
    }

    /**
     * Return the canonical date / time HTML for the current user / request.
     *
     * @return string
     */
    public function formatUserDateTimeHtml(): string
    {
        return '<time datetime="' . e($this->toISOString()) . '">' .
             e($this->formatUserDateTime()) . '</time>';
    }


    /**
     * Return the user locale.
     *
     * @return string
     */
    public function getUserLocale(): string
    {
        return App::getLocale();
    }

    /**
     * Return the user timezone.
     *
     * @todo This should be based on geo-ip of the current request.
     *
     * @return string
     */
    public function getUserTimezone(): string
    {
        return Config::get('abhayagiri.human_timezone');
    }
}
