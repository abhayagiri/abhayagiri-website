<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\News;
use App\Models\Reflection;
use App\Models\Talk;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page.
     *
     * @return \Illuminate\Http\View
     */
    public function index(): View
    {
        $mainPlaylistGroup = Talk::getLatestPlaylistGroup('main');
        return view('home.index', [
            'calendar' => Calendar::createFromUpcomingWeek(),
            'newsItems' => News::home()->get(),
            'reflection' => Reflection::public()->commonOrder()->first(),
            'talk' => Talk::latestTalks($mainPlaylistGroup)->first(),
        ]);
    }
}
