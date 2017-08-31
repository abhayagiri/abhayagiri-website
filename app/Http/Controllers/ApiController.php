<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\SubjectGroup;
use App\Models\Subject;
use App\Models\Tag;
use App\Models\Talk;
use App\Models\TalkType;

class ApiController extends Controller
{
    public function getAuthor(Request $request, $id)
    {
        return Author::findOrFail($id)->toJson();
    }

    public function getAuthors(Request $request)
    {
        return Author::orderBy('title')->get()->toJson();
    }

    public function getSubjectGroup(Request $request, $id)
    {
        return SubjectGroup::findOrFail($id)->toJson();
    }

    public function getSubjectGroups(Request $request)
    {
        return SubjectGroup::orderBy('rank')->orderBy('title_en')
            ->get()->toJson();
    }

    public function getSubject(Request $request, $id)
    {
        return Subject::findOrFail($id)->toJson();
    }

    public function getSubjects(Request $request)
    {
        $subjects = Subject::select();
        if ($subjectGroupId = $request->input('subjectGroupId')) {
            $subjects = $subjects->where('group_id', $subjectGroupId);
        }
        return $subjects->orderBy('rank')->orderBy('title_en')
            ->get()->toJson();
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

    public function getTags(Request $request, $subjectSlug)
    {
        $subject = Subject::where('slug', '=', $subjectSlug)->first();
        if (!$subject) {
            abort(404);
        }
        $tags = $subject->tags()->orderBy('rank')->orderBy('title_en')->get();
        return $tags->toJson();
    }

    public function getTalks(Request $request)
    {
        $talks = DB::table('talks');

        $authorId = $request->input('authorId');
        $talkTypeId = $request->input('typeId');
        $tagId = $request->input('tagId');

        if ($authorId) {
            $author = Author::findOrFail($authorId);
            $talks = $talks->where('talks.author', $author->title);
        } elseif ($talkTypeId) {
            $talks = $talks->where('talks.type_id', $talkTypeId);
        } elseif ($tagId) {
            $talks = $talks
                ->join('tag_talk', 'talks.id', '=', 'tag_talk.talk_id')
                ->join('tags', 'tags.id', '=', 'tag_talk.tag_id')
                ->where('tags.id', $tagId);
        }

        $searchText = trim((string) $request->input('searchText'));
        if ($searchText) {
            $talks = $talks->where(function ($query) use ($searchText) {
                // TODO should be in a helper function
                // TODO should also search tags, categories, etc.?
                $likeQuery = '%' . str_replace(['%', '_'], ['\%', '\_'], $searchText) . '%';
                $query->where('talks.title', 'LIKE', $likeQuery)
                      ->orWhere('author', 'LIKE', $likeQuery)
                      ->orWhere('body', 'LIKE', $likeQuery);
            });
        }

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $page = (int) $request->input('page');
        $pageSize = (int) $request->input('pageSize');
        if ($startDate) {
            $talks = $talks->where('talks.date', '>=', Carbon::createFromTimestamp((int) $startDate));
        }
        if ($endDate) {
            $talks = $talks->where('talks.date', '<', Carbon::createFromTimestamp((int) $endDate));
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
            ->orderBy('talks.date', 'desc')
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

    public function getTalk(Request $request, $talkId)
    {
        $talks = DB::table('talks');
        if (preg_match('/^([0-9]+)(-.*)?$/', $talkId, $matches)) {
            $talkId = (int) $matches[1];
            $talks = $talks->where('id', $talkId);
        } else {
            $talks = $talks->where('url_title', $talkId);
        }
        $talks = $this->remapTalks($talks);
        if (count($talks) > 0) {
            return response()->json($talks[0]);
        } else {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    public function getTalkType(Request $request, $id)
    {
        return TalkType::findOrFail($id)->toJson();
    }

    public function getTalkTypes(Request $request)
    {
        $talkTypes = TalkType::orderBy('rank')
            ->orderBy('title_en')->get();
        return $talkTypes->toJson();
    }

    protected function remapTalks($talks)
    {
        $talks = $this->remapKeys($talks->get(), [
            'body' => 'description',
        ])->map(function($talk) {
            $talk['author'] = Author::where('title', $talk['author'])->first();
            $talk['type'] = TalkType::where('id', $talk['type_id'])->first();
            $talk['mediaUrl'] = '/media/audio/' . $talk['mp3'];
            $talk['youTubeUrl'] = $talk['youtube_id'] ? ('https://youtu.be/' . $talk['youtube_id']) : null;
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
