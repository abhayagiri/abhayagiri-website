<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Genre;
use App\Models\Tag;
use App\Models\Talk;

class ApiController extends Controller
{

    public function getAuthors(Request $request)
    {
        $authors = Author::orderBy('title')->get();
        return $authors->toJson();
    }

    public function getGenres(Request $request)
    {
        $genres = Genre::orderBy('rank')->orderBy('title_en')->get();
        return $genres->toJson();
    }

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

    public function getTags(Request $request, $genreSlug)
    {
        $genre = Genre::where('slug', '=', $genreSlug)->first();
        if (!$genre) {
            abort(404);
        }
        $tags = $genre->tags()->orderBy('rank')->orderBy('title_en')->get();
        return $tags->toJson();
    }

    public function getTalks(Request $request)
    {
        $talks = DB::table('audio');

        $authorSlug = $request->input('authorSlug');
        $categorySlug = $request->input('categorySlug');
        $tagSlug = $request->input('tagSlug');

        if ($authorSlug) {
            $talks = $talks
                ->join('authors', 'audio.author', '=', 'authors.title')
                ->where('authors.url_title', $authorSlug);
        } elseif ($categorySlug) {
            $categoriesJson = file_get_contents(base_path('new/data/categories.json'));
            $categories = json_decode($categoriesJson, true);
            $categoryTitle = 'xxx';
            foreach ($categories as $category) {
                if ($category['slug'] == $categorySlug) {
                    $categoryTitle = $category['dbName'];
                    break;
                }
            }
            $talks = $talks->where('category', $categoryTitle);
        } elseif ($tagSlug) {
            $talks = $talks
                ->join('tag_talk', 'audio.id', '=', 'tag_talk.talk_id')
                ->join('tags', 'tags.id', '=', 'tag_talk.tag_id')
                ->where('tags.slug', $tagSlug);
        }

        $searchText = trim((string) $request->input('searchText'));
        if ($searchText) {
            $talks = $talks->where(function ($query) use ($searchText) {
                // TODO should be in a helper function
                // TODO should also search tags, categories, etc.?
                $likeQuery = '%' . str_replace(['%', '_'], ['\%', '\_'], $searchText) . '%';
                $query->where('audio.title', 'LIKE', $likeQuery)
                      ->orWhere('author', 'LIKE', $likeQuery)
                      ->orWhere('body', 'LIKE', $likeQuery);
            });
        }

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $page = (int) $request->input('page');
        $pageSize = (int) $request->input('pageSize');
        if ($startDate) {
            $talks = $talks->where('audio.date', '>=', Carbon::createFromTimestamp((int) $startDate));
        }
        if ($endDate) {
            $talks = $talks->where('audio.date', '<', Carbon::createFromTimestamp((int) $endDate));
        }
        if ($page < 1) {
            $page = 1;
        }
        if ($pageSize < 1 || $page > 1000) {
            // TODO better logic
            $pageSize = 10;
        }

        $total = $talks->count();
        $totalPages = ceil($total / $pageSize);
        $talks = $talks
            ->orderBy('audio.date', 'desc')
            ->offset(($page - 1) * $pageSize)
            ->limit($pageSize);
        $talks = $this->remapTalks($talks);
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

    public function getTalk(Request $request, $talkSlug)
    {
        $talks = DB::table('audio');
        if (preg_match('/^([0-9]+)(-.*)?$/', $talkSlug, $matches)) {
            $talkId = (int) $matches[1];
            $talks = $talks->where('id', $talkId);
        } else {
            $talks = $talks->where('url_title', $talkSlug);
        }
        $talks = $this->remapTalks($talks);
        if (count($talks) > 0) {
            $talk = $talks[0];
        } else {
            $talk = false;
        }
        return response()->json($talk);
    }

    protected function remapTalks($talks)
    {
        $talks = $this->remapKeys($talks->get(), [
            'body' => 'description',
        ])->map(function($talk) {
            $talk['author'] = Author::where('title', $talk['author'])->first();
            $talk['media_url'] = '/media/audio/' . $talk['mp3'];
            return $talk;
        });
        return $talks;
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
