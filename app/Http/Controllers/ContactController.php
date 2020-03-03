<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\ContactOption;
use Illuminate\Support\Facades\Lang;

class ContactController extends Controller
{
    /**
     * Display the contact page with contact options.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\View
     */
    public function __invoke(Request $request, ?ContactOption $contactOption = null): View
    {
        return view('contact.index')
            ->withContactOption($contactOption)
            ->withPreamble(Setting::findByKey(sprintf('contact.preamble_%s', Lang::getLocale())))
            ->withContactOptions(ContactOption::where('published', 1)->orderBy('rank')->orderBy('slug')->get());
    }
}
