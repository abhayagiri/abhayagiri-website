<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{

    public function getPages(Request $request, $page_slug, $subpage_slug = null)
    {
        $page = DB::table('pages')->where([
            'url_title' => $page_slug,
        ])->first();
        if (!$page) {
            abort(404);
        }
        if ($subpage_slug !== null) {
            $subpage = DB::table('subpages')->where([
                'page' => $page->url_title,
                'url_title' => $subpage_slug,
            ])->first();
            if (!$subpage) {
                abort(404);
            }
            $page_title = $subpage->title;
            $page_body = $subpage->body;
        } else {
            $page_title = $page->title;
            $page_body = $page->body;
        }
        return response()->json([
            'page_title' => $page_title,
            'page_body' => $page_body,
            'page_icon' => $page->icon,
            'banner_url' => $this->getBannerUrl($page),
        ]);
    }

    protected function getBannerUrl($page)
    {
        $path = 'media/images/banner/' . $page->url_title . '.jpg';
        if (file_exists(public_path($path))) {
            return '/' . $path;
        } else {
            return '/media/images/banner/home.jpg';
        }
    }

}
