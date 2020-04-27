<?php

namespace App\Http\Controllers;

use App\Models\ContactOption;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function index(): View
    {
        return view('contact.index')
            ->withPreamble(ContactOption::getPreamble())
            ->withContactOptions(ContactOption::where('published', 1)->orderBy('rank')->orderBy('slug')->get());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ContactOption $contactOption
     *
     * @return \Illuminate\Http\View
     */
    public function show(ContactOption $contactOption): View
    {
        return view('contact.show')->withContactOption($contactOption);
    }
}
