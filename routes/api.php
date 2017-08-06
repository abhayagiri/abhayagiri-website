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

Route::get('/genres', 'ApiController@getGenres');
Route::get('/subpages/{pageSlug}', 'ApiController@getSubpages');
Route::get('/subpages/{pageSlug}/{subpageSlug}', 'ApiController@getSubpage');
Route::get('/tags/{genreSlug}', 'ApiController@getTags');
Route::get('/talks', 'ApiController@getTalks');
Route::get('/talks/{tagSlug}', 'ApiController@getTalks');
