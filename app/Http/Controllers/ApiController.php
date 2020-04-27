<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Author;
use App\Models\ContactOption;
use App\Models\Playlist;
use App\Models\PlaylistGroup;
use App\Models\Redirect;
use App\Models\Setting;
use App\Models\Subject;
use App\Models\SubjectGroup;
use App\Models\Talk;
use App\Scopes\RankTitleEnScope;
use App\Scopes\TitleEnScope;
use App\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function getAlbum(Request $request, $id)
    {
        return $this->camelizeResponse(
            Album::with(['photos', 'thumbnail'])->findOrFail($id)
        );
    }

    public function getAlbums(Request $request)
    {
        $albums = Album::select('albums.*');

        $searchText = trim((string) $request->input('searchText'));

        if ($searchText) {
            $searchQuery = DB::table('albums')->distinct()->select('albums.id')
                ->join('album_photo', 'album_photo.album_id', '=', 'albums.id')
                ->join('photos', 'album_photo.photo_id', '=', 'photos.id')
                ->where(function ($query) use ($searchText) {
                    $likeQuery = '%' . Util::escapeLikeQueryText($searchText) . '%';
                    $query->where('albums.id', '=', $searchText)
                        ->orWhere('albums.title_en', 'LIKE', $likeQuery)
                        ->orWhere('albums.title_th', 'LIKE', $likeQuery)
                        ->orWhere('albums.description_en', 'LIKE', $likeQuery)
                        ->orWhere('albums.description_th', 'LIKE', $likeQuery)
                        ->orWhere('photos.caption_en', 'LIKE', $likeQuery)
                        ->orWhere('photos.caption_th', 'LIKE', $likeQuery);
                });
            $albumIds = $searchQuery->pluck('id')->toArray();
            $albums = $albums->whereIn('albums.id', $albumIds);
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
        $total = $albums->count();
        $totalPages = ceil($total / $pageSize);
        $albums = $albums
            ->byRank()
            ->offset(($page - 1) * $pageSize)
            ->limit($pageSize)
            ->with(['photos', 'thumbnail']);

        return [
            'searchText' => $searchText,
            'page' => $page,
            'pageSize' => $pageSize,
            'total' => $total,
            'totalPages' => $totalPages,
            'albums' => $this->camelizeResponse($albums->get()),
        ];
    }

    public function getAuthor(Request $request, $id)
    {
        return $this->camelizeResponse(Author::findOrFail($id));
    }

    public function getAuthors(Request $request)
    {
        $minTalks = $request->input('minTalks');
        $maxTalks = $request->input('maxTalks');

        if (! is_null($minTalks) || ! is_null($maxTalks)) {
            $authors = Author::withTalkCount();

            if (! is_null($minTalks)) {
                $minTalks = (int) $minTalks;
                $authors->having('talk_count', '>=', $minTalks);
            }

            if (! is_null($maxTalks)) {
                $maxTalks = (int) $maxTalks;
                $authors->having('talk_count', '<=', $maxTalks);
            }
            $authors->orderBy('rank')->orderBy('title_en');
        } else {
            $authors = Author::orderBy('rank');
        }

        return $this->camelizeResponse($authors->get());
    }

    public function getContactPreambles(Request $request)
    {
        return $this->camelizeResponse(
            Setting::where('key', 'like', 'contact.preamble_%')->get()
        );
    }

    public function getContactOption(Request $request, $slug)
    {
        return $this->camelizeResponse(
            ContactOption::whereSlug($slug)->first()
        );
    }

    public function getContactOptions(Request $request)
    {
        return $this->camelizeResponse(
            ContactOption::where('published', 1)->orderBy('rank')->orderBy('slug')->get()
        );
    }

    public function getPlaylistGroup(Request $request, $id)
    {
        return $this->camelizeResponse(
            PlaylistGroup::with(['playlists' => function ($query) {
                $query->withoutGlobalScope(TitleEnScope::class)
                    ->withGlobalScope(RankTitleEnScope::class, new RankTitleEnScope);
            }])
                ->findOrFail($id)
        );
    }

    public function getPlaylistGroups(Request $request)
    {
        return $this->camelizeResponse(
            PlaylistGroup::withoutGlobalScope(TitleEnScope::class)
                ->orderBy('rank')->orderBy('title_en')
            // TEMP add to make it easy to grab the first/default subject
                ->with(['playlists' => function ($query) {
                    $query->withoutGlobalScope(TitleEnScope::class)
                        ->withGlobalScope(RankTitleEnScope::class, new RankTitleEnScope);
                }])
                ->get()
        );
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

    public function getRedirect(Request $request, $from)
    {
        $to = Redirect::getRedirectFromPath($from);

        if ($to) {
            return response()->json($to);
        } else {
            abort(404);
        }
    }

    public function getSubjectGroup(Request $request, $id)
    {
        return $this->camelizeResponse(
            SubjectGroup::with(['subjects' => function ($query) {
                $query->withoutGlobalScope(TitleEnScope::class)
                    ->withGlobalScope(RankTitleEnScope::class, new RankTitleEnScope);
            }])
                ->findOrFail($id)
        );
    }

    public function getSubjectGroups(Request $request)
    {
        return $this->camelizeResponse(
            SubjectGroup::withoutGlobalScope(TitleEnScope::class)
                ->orderBy('rank')->orderBy('title_en')
            // TEMP add to make it easy to grab the first/default subject
                ->with(['subjects' => function ($query) {
                    $query->withoutGlobalScope(TitleEnScope::class)
                        ->withGlobalScope(RankTitleEnScope::class, new RankTitleEnScope);
                }])
                ->get()
        );
    }

    public function getSubject(Request $request, $id)
    {
        return $this->camelizeResponse(
            Subject::with('group')->findOrFail($id)
        );
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

    public function getTalks(Request $request)
    {
        $talks = Talk::select('talks.*')
            ->distinct()->public();

        if ($authorId = $request->input('authorId')) {
            $talks->where('talks.author_id', '=', $authorId);
        }

        if ($subjectId = $request->input('subjectId')) {
            $talks
                ->join('subject_talk', 'subject_talk.talk_id', '=', 'talks.id')
                ->where('subject_talk.subject_id', '=', $subjectId);
        }

        if ($playlistId = $request->input('playlistId')) {
            $talks
                ->join('playlist_talk', 'playlist_talk.talk_id', '=', 'talks.id')
                ->where('playlist_talk.playlist_id', '=', $playlistId);
        }

        if ($playlistGroupId = $request->input('playlistGroupId')) {
            $talks
                ->join('playlist_talk', 'playlist_talk.talk_id', '=', 'talks.id')
                ->join('playlists', 'playlists.id', '=', 'playlist_talk.playlist_id')
                ->where('playlists.group_id', '=', $playlistGroupId);
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
            $talks = $talks->where(
                'talks.recorded_on',
                '>=',
                Carbon::createFromTimestamp((int) $startDate)
            );
        }

        if ($endDate = $request->input('endDate')) {
            $talks = $talks->where(
                'talks.recorded_on',
                '<',
                Carbon::createFromTimestamp((int) $endDate)
            );
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
            ->postOrdered()
            ->offset(($page - 1) * $pageSize)
            ->limit($pageSize)
            ->with(['author', 'language', 'playlists', 'subjects']);
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

    public function getTalksLatest(Request $request)
    {
        $get = function ($key) {
            $playlistGroup = Talk::getLatestPlaylistGroup($key);
            $count = Talk::getLatestCount($key);
            $talks = Talk::latestTalks($playlistGroup)
                ->with(['author', 'language', 'playlists', 'subjects'])
                ->limit($count)
                ->get();

            return [$playlistGroup, $talks];
        };
        [$mainPlaylistGroup, $mainTalks] = $get('main');
        [$altPlaylistGroup, $altTalks] = $get('alt');

        return response()->json([
            'main' => [
                'playlistGroup' => $this->camelizeResponse($mainPlaylistGroup),
                'talks' => $this->camelizeResponse($mainTalks),
            ],
            'alt' => [
                'playlistGroup' => $this->camelizeResponse($altPlaylistGroup),
                'talks' => $this->camelizeResponse($altTalks),
            ],
            'authors' => Talk::getLatestBunch('authors'),
            'playlists' => Talk::getLatestBunch('playlists'),
            'subjects' => Talk::getLatestBunch('subjects'),
        ]);
    }

    public function getTalk(Request $request, $id)
    {
        $talk = Talk::select('talks.*')
            ->with(['author', 'language', 'playlists', 'subjects'])
            ->findOrFail($id);

        return $this->camelizeResponse($talk);
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
