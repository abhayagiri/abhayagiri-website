<?php

use App\Legacy;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::post('/books/cart/{id}', 'BookCartController@addBook')
    ->where('id', '[0-9]+');
Route::patch('/books/cart/{id}/{quantity}', 'BookCartController@updateBook')
    ->where('id', '[0-9]+');
Route::delete('/books/cart/{id}', 'BookCartController@deleteBook')
    ->where('id', '[0-9]+');
Route::post('/books/cart/request', 'BookCartController@sendRequest');

Route::get('/audio.rss', 'RssController@audio');
Route::get('/rss/audio.php', 'RssController@audio');
Route::get('/news.rss', 'RssController@news');
Route::get('/rss/news.php', 'RssController@news');
Route::get('/reflections.rss', 'RssController@reflections');
Route::get('/rss/reflections.php', 'RssController@reflections');

Route::post('/contact', 'ContactController@sendMessage');

Route::get('/version', 'UtilController@version');

Route::get('/mahapanel_bypass', 'UtilController@mahapanelBypass');

/*
|--------------------------------------------------------------------------
| Legacy Routes
|--------------------------------------------------------------------------
|
| Routes for the older PHP application.
*/

Route::get('/th/php/ajax.php', function() {
    return Legacy::response('th/php/ajax.php', false);
});

Route::get('/th/php/datatables.php', function() {
    return Legacy::response('th/php/datatables.php', false);
});

Route::any('/th', function() {
    return Legacy::response('th/index.php', '');
});

Route::any('/th/{page}', function($page) {
    return Legacy::response('th/index.php', $page);
})->where('page', '.*');

Route::get('/php/ajax.php', function() {
    return Legacy::response('php/ajax.php', false);
});

Route::get('/php/datatables.php', function() {
    return Legacy::response('php/datatables.php', false);
});

Route::any('{page}', function($page) {
    return Legacy::response('index.php', $page);
})->where('page', '.*');
