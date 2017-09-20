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
Route::post('/th/contact', 'ContactController@sendMessage');

Route::get('/error', 'UtilController@error');
Route::get('/version', 'UtilController@version');

// Admin Interface Routes
Route::group(['prefix' => 'admin', 'middleware' => ['admin', 'secure_admin']], function() {

    foreach (config('admin.models') as $model) {
        if (!array_get($model, 'route', true)) {
            continue;
        }
        if (array_get($model, 'super_admin')) {
            $groupOptions = [ 'middleware' => 'super_admin' ];
        } else {
            $groupOptions = [];
        }
        Route::group($groupOptions, function() use ($model) {

            $routeName = $model['name'];
            $modelClassName = studly_case(str_singular($routeName));
            $controllerName = 'Admin\\' . $modelClassName . 'CrudController';
            CRUD::resource($routeName, $controllerName);

            if (array_get($model, 'restore', true)) {
                $restorePath = $routeName . '/{id}/restore';
                $restoreName = 'crud.' . $routeName . '.restore';
                Route::get($restorePath, $controllerName . '@restore')
                    ->name($restoreName);
            }
        });
    }

    Route::resource('setting', '\Backpack\Settings\app\Http\Controllers\SettingCrudController');

    Route::get('dashboard', '\Backpack\Base\app\Http\Controllers\AdminController@dashboard');

    Route::post('talks/search', 'Admin\TalkCrudController@searchAjax');

});

// Admin Authentication
Route::group(['prefix' => 'admin', 'middleware' => 'secure_admin'], function() {
    Route::get('', function () {
        if (\Auth::check()) {
            return redirect('/admin/dashboard');
        } else {
            return redirect('/admin/login');
        }
    });
    Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
    Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
    Route::post('login', ['as' => 'login.post', 'uses' => 'Auth\LoginController@login']);
    Route::get('logout', 'Auth\LoginController@logout');
    Route::post('logout', 'Auth\LoginController@logout');
    Route::get('login/google', 'Auth\LoginController@redirectToProvider');
    Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');
    Route::get('login/dev-bypass', 'Auth\LoginController@devBypass');
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
