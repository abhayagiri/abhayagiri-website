<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Playlist;
use App\Models\PlaylistGroup;
use App\Models\Resident;
use App\Models\Subject;
use App\Models\SubjectGroup;
use App\Models\Subpage;
use App\Models\Tag;
use App\Models\Talk;
use App\Models\TalkType;
use App\Scopes\TitleEnScope;
use App\Scopes\RankTitleEnScope;
use App\Util;

class ApiController extends Controller
{
    public function getAuthor(Request $request, $id)
    {
        return $this->camelizeResponse(Author::findOrFail($id));
    }

    public function getAuthors(Request $request)
    {
        $minTalks = $request->input('minTalks');
        $maxTalks = $request->input('maxTalks');
        if (!is_null($minTalks) || !is_null($maxTalks)) {
            $authors = Author::withTalkCount();
            if (!is_null($minTalks)) {
                $minTalks = (int) $minTalks;
                $authors->having('talk_count', '>=', $minTalks);
            }
            if (!is_null($maxTalks)) {
                $maxTalks = (int) $maxTalks;
                $authors->having('talk_count', '<=', $maxTalks);
            }
            $authors->orderBy('authors.title_en');
        } else {
            $authors = Author::orderBy('title_en');
        }
        return $this->camelizeResponse($authors->get());
    }

    public function getPlaylistGroup(Request $request, $id)
    {
        return $this->camelizeResponse(
            PlaylistGroup::with(['playlists' => function($query) {
                $query->withoutGlobalScope(TitleEnScope::class)
                    ->withGlobalScope(RankTitleEnScope::class, new RankTitleEnScope);
            }])
            ->findOrFail($id));
    }

    public function getPlaylistGroups(Request $request)
    {
        return $this->camelizeResponse(
            PlaylistGroup::withoutGlobalScope(TitleEnScope::class)
            ->orderBy('rank')->orderBy('title_en')
            // TEMP add to make it easy to grab the first/default subject
            ->with(['playlists' => function($query) {
                $query->withoutGlobalScope(TitleEnScope::class)
                    ->withGlobalScope(RankTitleEnScope::class, new RankTitleEnScope);
            }])
            ->get());
    }

    public function getPlaylist(Request $request, $id)
    {
        return $this->camelizeResponse(Playlist::findOrFail($id));
    }

    public function getPlaylists(Request $request, $id = null)
    {
        $playlists = Playlist::withoutGlobalScope(TitleEnScope::class)
            ->select();
        if ($id) {
            $playlists->where('group_id', $id);
        }
        return $this->camelizeResponse($playlists
            ->public()
            ->orderBy('rank')->orderBy('title_en')
            ->with('group')
            ->get());
    }

    public function getSubjectGroup(Request $request, $id)
    {
        return $this->camelizeResponse(
            SubjectGroup::with(['subjects' => function($query) {
                $query->withoutGlobalScope(TitleEnScope::class)
                    ->withGlobalScope(RankTitleEnScope::class, new RankTitleEnScope);
            }])
            ->findOrFail($id));
    }

    public function getSubjectGroups(Request $request)
    {
        return $this->camelizeResponse(
            SubjectGroup::withoutGlobalScope(TitleEnScope::class)
            ->orderBy('rank')->orderBy('title_en')
            // TEMP add to make it easy to grab the first/default subject
            ->with(['subjects' => function($query) {
                $query->withoutGlobalScope(TitleEnScope::class)
                    ->withGlobalScope(RankTitleEnScope::class, new RankTitleEnScope);
            }])
            ->get());
    }

    public function getSubject(Request $request, $id)
    {
        return $this->camelizeResponse(
            Subject::with('group')->findOrFail($id));
    }

    public function getSubjects(Request $request, $id = null)
    {
        $subjects = Subject::withoutGlobalScope(TitleEnScope::class)
            ->select();
        if ($id) {
            $subjects->where('group_id', $id);
        }
        return $this->camelizeResponse($subjects
            ->orderBy('rank')->orderBy('title_en')
            ->with('group')
            ->get());
    }

    public function getSubpages(Request $request, $page)
    {
        return $this->camelizeResponse(
            Subpage::public()->where('page', $page)->get());
    }

    public function getSubpage(Request $request, $page, $subpath)
    {
        if ($page === 'community' && starts_with($subpath, 'residents/')) {
            return $this->camelizeResponse(Resident
                ::where('slug', preg_replace('_^residents/_', '', $subpath))
                ->firstOrFail());
        }
        return $this->camelizeResponse(Subpage::public()
            ->where('page', $page)
            ->where('subpath', $subpath)
            ->firstOrFail());
    }

    public function getTalks(Request $request)
    {
        $talks = Talk::select('talks.*')->public();

        if ($authorId = $request->input('authorId')) {
            $talks = $talks->where('talks.author_id', $authorId);
        }
        if ($talkTypeId = $request->input('typeId')) {
            $talks = $talks->where('talks.type_id', $talkTypeId);
        }
        if ($subjectId = $request->input('subjectId')) {
            // $subject = Subject::findOrFail($subjectId);
            // $talkIds = $subject->getTalkIds();
            // $talks = $talks->whereIn('talks.id', $talkIds);
            $talks = $talks->join('tag_talk', 'tag_talk.talk_id', '=', 'talks.id');
            $talks = $talks->join('subject_tag', 'subject_tag.tag_id', '=', 'tag_talk.tag_id');
            $talks = $talks->where('subject_tag.subject_id', $subjectId);
        }
        if ($playlistId = $request->input('playlistId')) {
            $talks = $talks->join('playlist_talk', 'playlist_talk.talk_id', '=', 'talks.id');
            $talks = $talks->where('playlist_talk.playlist_id', $playlistId);
        }
        if ($request->input('latest')) {
            $talks = $talks->latestVisible();
        }

        $searchText = trim((string) $request->input('searchText'));
        if ($searchText) {
            $likeQuery = '%' . Util::escapeLikeQueryText($searchText) . '%';
            $talks = $talks->join('authors', 'authors.id', '=', 'talks.author_id');
            $talks = $talks->where(function ($query) use ($likeQuery, $searchText) {
                $query->where('talks.id', '=', $searchText)
                      ->orWhere('talks.title_en', 'LIKE', $likeQuery)
                      ->orWhere('talks.title_th', 'LIKE', $likeQuery)
                      ->orWhere('talks.description_en', 'LIKE', $likeQuery)
                      ->orWhere('talks.description_th', 'LIKE', $likeQuery)
                      ->orWhere('authors.title_en', 'LIKE', $likeQuery)
                      ->orWhere('authors.title_th', 'LIKE', $likeQuery);
            });
        }

        if ($startDate = $request->input('startDate')) {
            $talks = $talks->where('talks.recorded_on', '>=',
                Carbon::createFromTimestamp((int) $startDate));
        }
        if ($endDate = $request->input('endDate')) {
            $talks = $talks->where('talks.recorded_on', '<',
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
            ->latest()
            ->offset(($page - 1) * $pageSize)
            ->limit($pageSize)
            ->with('type')
            ->with('author')
            ->with('language')
            ->with('tags');
        // return ;
        // $talks = $this->remapTalks($talks);
        $output = [
            'request' => $request->all(),
            'page' => $page,
            'pageSize' => $pageSize,
            'total' => $total,
            'totalPages' => $totalPages,
            'result' => $this->camelizeResponse($talks->get()),
        ];
        return response()->json($output);
    }

    public function getTalk(Request $request, $id)
    {
        $talk = Talk::select()
            ->with('type')
            ->with('author')
            ->with('tags')
            ->findOrFail($id);
        return $this->camelizeResponse($talk);
    }

    public function getTalkType(Request $request, $id)
    {
        return $this->camelizeResponse(TalkType::findOrFail($id));
    }

    public function getTalkTypes(Request $request)
    {
        return $this->camelizeResponse(
            TalkType::orderBy('rank')->orderBy('title_en')->get());
    }

    protected function remapTalks($talks)
    {
        return $talks->map(function($talk) {
            return $this->remapTalk($talk);
        });
    }

    protected function remapTalk($talk)
    {
        $subjects = $this->camelizeResponse($talk->getSubjects()
            ->orderBy('title_en')->get());
        $date = $talk->recorded_on->format('F j, Y');
        $author = $this->camelizeResponse($talk->author);
        $talk = $talk->toArray();
        $talk['author'] = $author;
        $talk['subjects'] = $subjects;
        $talk['date'] = $date;
        $talk['title'] = $talk['title_en'];
        $talk['description'] = $talk['body'];
        $talk['imageUrl'] = $talk['image_url'];
        $talk['mediaUrl'] = $talk['media_url'];
        $talk['youTubeUrl'] = $talk['youtube_id'] ? ('https://youtu.be/' .
            $talk['youtube_id']) : null;
        if ($talk['mp3']) {
            $ext = substr($talk['mp3'], -3);
            $dateStr = (new Carbon($talk['recorded_on']))->toDateString();
            $talk['filename'] = $dateStr . ' ' . $talk['title'] . '.' . $ext;
        }
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

    protected function camelizeResponse($response)
    {
        if (method_exists($response, 'toArray')) {
            return $this->camelizeArray($response->toArray());
        } else {
            return $response;
        }
    }

    protected function camelizeArray($array)
    {
        $camelArray = [];
        foreach ($array as $name => $value) {
            if (is_array($value)) {
                $value = $this->camelizeArray($value);
            }
            $camelArray[camel_case($name)] = $value;
        }
        return $camelArray;
    }
}
