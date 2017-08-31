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
        $talks = Talk::select();

        if ($authorId = $request->input('authorId')) {
            $author = Author::findOrFail($authorId);
            $talks = $talks->where('talks.author', $author->title);
        }
        if ($talkTypeId = $request->input('typeId')) {
            $talks = $talks->where('talks.type_id', $talkTypeId);
        }
        if ($subjectId = $request->input('subjectId')) {
            $subject = Subject::findOrFail($subjectId);
            $talks = $subject->getTalks();
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

        if ($startDate = $request->input('startDate')) {
            $talks = $talks->where('talks.date', '>=',
                Carbon::createFromTimestamp((int) $startDate));
        }
        if ($endDate = $request->input('endDate')) {
            $talks = $talks->where('talks.date', '<',
                Carbon::createFromTimestamp((int) $endDate));
        }
        $page = (int) $request->input('page');
        if ($page < 1) {
            $page = 1;
        }
        $pageSize = (int) $request->input('pageSize');
        if ($pageSize < 1 || $page > 100) {
            // TODO better logic
            $pageSize = 10;
        }

        $total = $talks->count();
        $totalPages = ceil($total / $pageSize);
        $talks = $talks
            ->orderBy('talks.date', 'desc')
            ->offset(($page - 1) * $pageSize)
            ->limit($pageSize)
            ->with('type')
            ->with('tags');
        // return ;
        // $talks = $this->remapTalks($talks);
        $output = [
            'request' => $request->all(),
            'page' => $page,
            'pageSize' => $pageSize,
            'total' => $total,
            'totalPages' => $totalPages,
            'result' => $this->remapTalks($talks->get()),
        ];
        return response()->json($output);
    }

    public function getTalk(Request $request, $id)
    {
        $talk = Talk::select()
            ->with('type')
            ->with('tags')
            ->findOrFail($id);
        return $this->remapTalk($talk)->toJson();
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
        return $talks->map(function($talk) {
            return $this->remapTalk($talk);
        });
    }

    protected function remapTalk($talk)
    {
        $talk->subjects = $talk->getSubjects()->orderBy('title_en')->get();
        $talk->author = Author::where('title', $talk->author)->first();
        $talk->mediaUrl = '/media/audio/' . $talk->mp3;
        $talk->youTubeUrl = $talk->youtube_id ? ('https://youtu.be/' . $talk->youtube_id) : null;
        $talk->description = $talk->body;
        unset($talk->body);
        return $talk;
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
}
