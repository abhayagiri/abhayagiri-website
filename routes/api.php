<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/authors', 'ApiController@getAuthors');
// Filters:
//   minTalks=:integer
//   maxTalks=:integer
Route::get('/authors/{id}', 'ApiController@getAuthor');
Route::get('/playlist-groups', 'ApiController@getPlaylistGroups');
Route::get('/playlist-groups/{id}', 'ApiController@getPlaylistGroup');
Route::get('/playlist-groups/{id}/playlists', 'ApiController@getPlaylists');
Route::get('/playlists', 'ApiController@getPlaylists');
Route::get('/playlists/{id}', 'ApiController@getPlaylist');
Route::get('/subject-groups', 'ApiController@getSubjectGroups');
Route::get('/subject-groups/{id}', 'ApiController@getSubjectGroup');
Route::get('/subject-groups/{id}/subjects', 'ApiController@getSubjects');
Route::get('/subjects', 'ApiController@getSubjects');
Route::get('/subjects/{id}', 'ApiController@getSubject');
Route::get('/subpages/{pageSlug}', 'ApiController@getSubpages');
Route::get('/subpages/{pageSlug}/{subpageSlug}', 'ApiController@getSubpage');
Route::get('/talk-types', 'ApiController@getTalkTypes');
Route::get('/talk-types/{id}', 'ApiController@getTalkType');
Route::get('/talks', 'ApiController@getTalks');
// Filters:
//   authorId=:id
//   subjectId=:id
//   typeId=:id
//   searchText=:string
//   startDate=:timestamp
//   endDate=:timestamp
//   page=:integer
//   pageSize=:integer
Route::get('/talks/{id}', 'ApiController@getTalk');
