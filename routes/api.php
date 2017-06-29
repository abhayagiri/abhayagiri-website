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

Route::get('/pages/{page_slug}', 'ApiController@getPage');
Route::get('/subpages/{page_slug}/{subpage_slug}', 'ApiController@getSubPage');

