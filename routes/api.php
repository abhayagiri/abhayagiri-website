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
Route::get('/authors/{id}', 'ApiController@getAuthor');
Route::get('/subjects-groups', 'ApiController@getSubjectGroups');
Route::get('/subjects-groups/{id}', 'ApiController@getSubjectGroup');
Route::get('/subjects', 'ApiController@getSubjects');
// Filter by subject group: /subjects?subjectGroupId=:id
Route::get('/subjects/{id}', 'ApiController@getSubject');
Route::get('/subpages/{pageSlug}', 'ApiController@getSubpages');
Route::get('/subpages/{pageSlug}/{subpageSlug}', 'ApiController@getSubpage');
Route::get('/talk-types', 'ApiController@getTalkTypes');
Route::get('/talk-types/{id}', 'ApiController@getTalkType');
Route::get('/talks', 'ApiController@getTalks');
// Filter by author: /talks?authorId=:id
// Filter by subject: /talks?subjectId=:id
// Filter by type: /talks?typeId=:id
Route::get('/talks/{id}', 'ApiController@getTalk');
