<?php

namespace App\Http\Controllers;

use App\Feed;
use Illuminate\Http\Request;

use Illuminate\Http\Response;

class RssController extends Controller
{
    public function audio(Request $request)
    {
        return $this->rssResponse(Feed::getAudioFeed());
    }

    public function news(Request $request)
    {
        return $this->rssResponse(Feed::getNewsFeed());
    }

    public function reflections(Request $request)
    {
        return $this->rssResponse(Feed::getReflectionsFeed());
    }

    protected function rssResponse($data)
    {
        return (new Response($data))
            ->header('Content-Type', 'application/rss+xml; charset=utf-8');
    }
}
