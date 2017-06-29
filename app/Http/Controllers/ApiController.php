<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{

    public function getPage(Request $request, $page_slug)
    {
        $page = $this->getPageFromSlug($page_slug);
        $subpages = DB::table('subpages')->where([
            'page' => $page->url_title,
            'language' => 'English', # TODO figure out Thai language
        ])->get();
        return response()->json([
            'page_title' => $page->title,
            'page_body' => $page->body,
            'page_icon' => $page->icon,
            'banner_url' => $this->getBannerUrl($page),
            'subpages' => $this->remapKeysFromDb($subpages),
        ]);
    }

    public function getSubPage(Request $request, $page_slug, $subpage_slug)
    {
        $page = $this->getPageFromSlug($page_slug);
        $subpage = DB::table('subpages')->where([
            'page' => $page->url_title,
            'url_title' => $subpage_slug,
        ])->first();
        if (!$subpage) {
            abort(404);
        }
        return response()->json([
            'page_title' => $subpage->title,
            'page_body' => $subpage->body,
        ]);
    }

    protected function getPageFromSlug($page_slug)
    {
        $page = DB::table('pages')->where([
            'url_title' => $page_slug,
        ])->first();
        if ($page) {
            return $page;
        } else {
            abort(404);
        }
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

    protected $remapKeysMap = [
        'url_title' => 'slug',
        'body' => 'page_body',
        'title' => 'page_title',
    ];

    protected function remapKeysFromDb($collection)
    {
        // Remap url_title -> slug
        return $collection->map(function ($item, $key) {
            $item = (array) $item;
            foreach ($this->remapKeysMap as $oldKey => $newKey) {
                if (array_key_exists($oldKey, $item)) {
                    $item[$newKey] = $item[$oldKey];
                    unset($item[$oldKey]);
                }
            }
            return $item;
        });
    }

}
