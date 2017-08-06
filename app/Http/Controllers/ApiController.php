<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{

    public function getSubpages(Request $request, $pageSlug)
    {
        $subpages = DB::table('subpages')->where([
            'page' => $pageSlug,
            'language' => 'English',
        ])->get();
        if ($subpages->count()) {
            $subpages = $subpages->map(function ($subpage) {
                return $this->remapSubpage($subpage);
            });
            return response()->json($subpages);
        } else {
            abort(404);
        }
    }

    public function getSubpage(Request $request, $pageSlug, $subpageSlug)
    {
        $subpage = DB::table('subpages')->where([
            'page' => $pageSlug,
            'url_title' => $subpageSlug,
            'language' => 'English',
        ])->first();
        if ($subpage) {
            return response()->json($this->remapSubpage($subpage));

        } else {
            abort(404);
        }
    }

    public function getTalks(Request $request)
    {
        $authorId = $request->input('author');
        $category = $request->input('category'); // TODO temporary
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $genre = $request->input('genre'); // TODO
        $page = (int) $request->input('page');
        $pageSize = (int) $request->input('pageSize');
        $searchText = trim((string) $request->input('searchText'));
        $talks = DB::table('audio');
        if ($authorId) {
            $author = DB::table('authors')->where(['id' => $authorId])->first();
            $talks = $talks->where('author', '=', $author ? $author->title : null);
        }
        if ($category && strtolower($category) != 'all') {
            $talks = $talks->where('category', '=', $category);
        }
        if ($startDate) {
            $talks = $talks->where('date', '>=', Carbon::createFromTimestamp((int) $startDate));
        }
        if ($endDate) {
            $talks = $talks->where('date', '<', Carbon::createFromTimestamp((int) $endDate));
        }
        if ($page < 1) {
            $page = 1;
        }
        if ($pageSize < 1 || $page > 1000) {
            // TODO better logic
            $pageSize = 20;
        }
        if ($searchText) {
            $talks = $talks->where(function ($query) use ($searchText) {
                // TODO should be in a helper function
                $likeQuery = '%' . str_replace(['%', '_'], ['\%', '\_'], $searchText) . '%';
                $query->where('title', 'LIKE', $likeQuery)
                      ->orWhere('author', 'LIKE', $likeQuery)
                      ->orWhere('body', 'LIKE', $likeQuery);
            });
        }
        $total = $talks->count();
        $totalPages = ceil($total / $pageSize);
        $talks = $talks
            ->orderBy('date', 'desc')
            ->offset(($page - 1) * $pageSize)
            ->limit($pageSize);
        $talks = $this->remapKeys($talks->get(), [
            'body' => 'description',
        ])->map(function($talk) {
            $talk['author'] = DB::table('authors')->where(['title' => $talk['author']])->first();
            $talk['media_url'] = '/media/audio/' . $talk['mp3'];
            return $talk;
        });
        $output = [
            'request' => $request->all(),
            'page' => $page,
            'pageSize' => $pageSize,
            'total' => $total,
            'totalPages' => $totalPages,
            'result' => $talks,
        ];
        return response()->json($output);
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

    protected function remapSubpage($subpage)
    {
        $result = [
            'id' => $subpage->id,
            'slug' => $subpage->url_title,
            'page' => $subpage->page,
            'titleEn' => $subpage->title,
            'titleTh' => null,
            'bodyEn' => $subpage->body,
            'bodyTh' => null,
        ];
        $thaiSubpage = DB::table('subpages')->where([
            'page' => $subpage->page,
            'url_title' => $subpage->url_title + '-thai',
            'language' => 'Thai',
        ])->first();
        if ($thaiSubpage) {
            $result['titleTh'] = $thaiSubpage->title;
            $result['bodyTh'] = $thaiSubpage->body;
        }
        return $result;
    }

    protected function remapKeys($collection, $mapping)
    {
        return $collection->map(function ($item, $key) use ($mapping) {
            $item = (array) $item;
            foreach ($mapping as $oldKey => $newKey) {
                if (array_key_exists($oldKey, $item)) {
                    $item[$newKey] = $item[$oldKey];
                    unset($item[$oldKey]);
                }
            }
            return $item;
        });
    }
}
