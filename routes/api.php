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
Route::get('/playlists', 'ApiController@getPlaylists');
Route::get('/playlists/{id}', 'ApiController@getPlaylist');
Route::get('/subjects-groups', 'ApiController@getSubjectGroups');
Route::get('/subjects-groups/{id}', 'ApiController@getSubjectGroup');
Route::get('/subjects', 'ApiController@getSubjects');
// Filters:
//   subjectGroupId=:id
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
