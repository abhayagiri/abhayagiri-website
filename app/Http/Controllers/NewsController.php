<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\View\View;

class NewsController extends Controller
{

    /**
     * Display a listing of news articles.
     *
     * @return \Illuminate\Http\View
     */
    public function index(): View
    {
        $this->authorize('viewAny', News::class);
        $news = News::public()->postOrdered()->paginate(10);
        return view('news.index', ['news' => $news]);
    }

    /**
     * Display the specified news article.
     *
     * @param \App\Models\News $news
     * @return \Illuminate\View\View
     */
    public function show(News $news): View
    {
        $this->authorize('view', $news);
        return view('news.show', ['news' => $news]);
    }
}
