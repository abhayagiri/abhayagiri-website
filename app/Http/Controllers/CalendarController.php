<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Carbon\Carbon;
use Illuminate\View\View;

class CalendarController extends Controller
{
    /**
     * Display the calendar for the specified day.
     *
     * @param  int  $year
     * @param  int  $month
     * @param  int  $day
     *
     * @return \Illuminate\Http\View
     */
    public function day(int $year, int $month, int $day): View
    {
        return view('calendar.day', [
            'calendar' => Calendar::createFromDay($year, $month, $day),
            'queryCalendar' => Calendar::createFromCalendarMonth($year, $month),
        ]);
    }

    /**
     * Display the calendar for the current month.
     *
     * @return \Illuminate\Http\View
     */
    public function index(): View
    {
        $today = Calendar::getToday();
        return $this->month($today->year, $today->month);
    }

    /**
     * Display the calendar for the specified month.
     *
     * @param  int  $year
     * @param  int  $month
     *
     * @return \Illuminate\Http\View
     */
    public function month(int $year, int $month): View
    {
        return view('calendar.month', [
            'calendar' => Calendar::createFromMonth($year, $month),
            'queryCalendar' => Calendar::createFromCalendarMonth($year, $month),
        ]);
    }
}
