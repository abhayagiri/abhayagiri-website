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
Route::get('/subjects', 'ApiController@getSubjectGroups'); // get subject groups
Route::get('/subjects/{id}', 'ApiController@getSubjects'); // get subjects from subject group
Route::get('/subpages/{pageSlug}', 'ApiController@getSubpages');
Route::get('/subpages/{pageSlug}/{subpageSlug}', 'ApiController@getSubpage');
Route::get('/talks', 'ApiController@getTalks');
// Filtered by category: /talks?categorySlug=:slug
// Filtered by author: /talks?authorId=:id
// Filtered by subject: /talks?subjectId=:id
Route::get('/talks/{id}', 'ApiController@getTalk');
