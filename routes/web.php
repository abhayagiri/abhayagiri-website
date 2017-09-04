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

Route::get('/error', 'UtilController@error');
Route::get('/version', 'UtilController@version');

Route::get('/mahapanel_bypass', 'UtilController@mahapanelBypass');

Route::get('/mahapanel/login', 'MahapanelController@login');
Route::get('/mahapanel/logout', 'MahapanelController@logout');

// Admin Interface Routes
Route::group(['prefix' => 'admin', 'middleware' => ['admin', 'secureadmin']], function() {
    CRUD::resource('authors', 'Admin\AuthorCrudController');
    CRUD::resource('playlists', 'Admin\PlaylistCrudController');
    Route::resource('setting', '\Backpack\Settings\app\Http\Controllers\SettingCrudController');
    CRUD::resource('subject-groups', 'Admin\SubjectGroupCrudController');
    CRUD::resource('subjects', 'Admin\SubjectCrudController');
    CRUD::resource('tags', 'Admin\TagCrudController');
    CRUD::resource('talk-types', 'Admin\TalkTypeCrudController');
    CRUD::resource('talks', 'Admin\TalkCrudController');
});

// Admin Authentication
Route::group(['prefix' => 'admin', 'middleware' => 'secureadmin'], function() {
    Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
    Route::post('login', ['as' => 'login.post', 'uses' => 'Auth\LoginController@login']);
    Route::get('logout', 'Auth\LoginController@logout');
    Route::post('logout', 'Auth\LoginController@logout');
    Route::get('login/google', 'Auth\LoginController@redirectToProvider');
    Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');
});

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

foreach (['ajax', 'columns', 'custom', 'dashboard', 'datatables',
          'dropdowns', 'exists', 'form', 'options', 'pages', 'profile',
          'table', 'upload'] as $php) {
    $script = 'mahapanel/php/' . $php . '.php';
    Route::any('/' . $script, function() use ($script) {
        return Legacy::response($script, false);
    });
}

Route::any('/mahapanel/{page}', function($page) {
    return Legacy::response('mahapanel/index.php', $page);
})->where('page', '.*');

Route::any('/mahapanel', function() {
    return Legacy::response('mahapanel/index.php', '');
});

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

Route::any('{page}', function($page) {
    return Legacy::response('index.php', $page);
})->where('page', '.*');
