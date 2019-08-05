<?php

use App\Legacy;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

foreach (['en', 'th'] as $lng) {

    if ($lng === 'en') {
        $options = [];
    } else { // th
        $options = [
            'prefix' => 'th',
            'middleware' => 'thai',
        ];
    }

    Route::group($options, function () use ($lng) {

        $publicMethods = ['show'];

        Route::resource('subpages', 'SubpageController', [
            'as' => $lng,
        ])->only($publicMethods);

        # TODO temporary testing prefix, e.g., /a/community/residents
        Route::prefix('a')->group(function () use ($lng) {
            Route::get('{path}', 'SubpagePathController')
                ->name($lng . '.subpages.path')
                ->where('path', '.*');
        });
    });
}

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
Route::post('/th/contact', 'ContactController@sendMessage');

Route::get('/error', 'UtilController@error');
Route::get('/version', 'UtilController@version');

Route::get('/audio/{all}', 'LinkRedirectController@redirect')
    ->where('all', '(.*)');
Route::get('/th/audio/{all}', 'LinkRedirectController@redirect')
    ->where('all', '(.*)');

/*
|--------------------------------------------------------------------------
| Legacy Routes
|--------------------------------------------------------------------------
|
| Routes for the legacy codebase.
*/

Route::get('/th/php/ajax.php', function() {
    App::setLocale('th');
    return Legacy::response('th/php/ajax.php', false);
});

Route::get('/th/php/datatables.php', function() {
    App::setLocale('th');
    return Legacy::response('th/php/datatables.php', false);
});

Route::any('/th', function() {
    App::setLocale('th');
    return Legacy::response('th/index.php', '');
});

Route::any('/th/{page}', function($page) {
    App::setLocale('th');
    return Legacy::response('th/index.php', $page);
})->where('page', '.*');

Route::get('/php/ajax.php', function() {
    return Legacy::response('php/ajax.php', false);
});

Route::get('/php/datatables.php', function() {
    return Legacy::response('php/datatables.php', false);
});

Route::any('{page}', function($page = '') {
    $redirect = \App\Models\Redirect::getRedirectFromPath($page);
    if ($redirect) {
        return redirect($redirect);
    } else {
        return Legacy::response('index.php', $page);
    }
})->where('page', '.*');
