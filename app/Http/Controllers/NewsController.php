<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends Controller
{
    /**
     * Return an image response for the news.
     *
     * @param  \App\Models\News  $news
     * @param  string  $preset
     * @param  string  $format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function image(News $news, string $preset, string $format): Response
    {
        $this->authorize('view', $news);
        return app('imageCache')->getModelImageResponse($news, $preset, $format);
    }

    /**
     * Display a listing of news articles.
     *
     * @return \Illuminate\Http\View
     */
    public function index(): View
    {
        $this->authorize('viewAny', News::class);
        return view('news.index')
            ->withNews(News::public()->commonOrder()->paginate(10));
    }

    /**
     * Display the specified news article.
     *
     * @param \App\Models\News $news
     *
     * @return \Illuminate\View\View
     */
    public function show(News $news): View
    {
        $this->authorize('view', $news);
        return view('news.show')
            ->withNews($news)
            ->withAssociated($news->getAssociated(10));
    }
}
