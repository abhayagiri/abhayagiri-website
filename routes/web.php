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
| These are routes that are duplicated for each language that we handle:
| 'en' and 'th'.
|
*/

foreach (['th', 'en'] as $lng) {
    $options = $lng === 'en' ? [] : ['prefix' => 'th', 'middleware' => 'thai'];
    Route::group($options, function () use ($lng) {
        $lngPrefix = $lng === 'en' ? '' : '/th';
        $namePrefix = $lng === 'en' ? '' : 'th.';

        // Book cart - these need to be before the books resource route.
        Route::get('books/select', 'BookCartController@show')
            ->name($namePrefix . 'books.cart.show');
        Route::post('books/select', 'BookCartController@add')
            ->name($namePrefix . 'books.cart.add');
        Route::put('books/select', 'BookCartController@update')
            ->name($namePrefix . 'books.cart.update');
        Route::delete('books/select', 'BookCartController@destroy')
            ->name($namePrefix . 'books.cart.destroy');
        Route::get('books/request', 'BookCartController@editRequest')
            ->name($namePrefix . 'books.cart.submit');
        Route::post('books/request', 'BookCartController@sendRequest')
            ->name($namePrefix . 'books.cart.submit');

        // Resources
        $options = $lng === 'en' ? [] : ['as' => 'th'];

        Route::resource('books', 'BookController', $options)
            ->only(['index', 'show']);

        Route::resource('reflections', 'ReflectionController', $options)
            ->only(['index', 'show']);
        Route::get('reflections.atom', 'FeedController@reflectionsAtom')->name($namePrefix . 'reflections.atom');
        Route::get('reflections.rss', 'FeedController@reflectionsRss')->name($namePrefix . 'reflections.rss');

        Route::resource('news', 'NewsController', $options)
            ->only(['index', 'show']);
        Route::get('news.atom', 'FeedController@newsAtom')->name($namePrefix . 'news.atom');
        Route::get('news.rss', 'FeedController@newsRss')->name($namePrefix . 'news.rss');

        Route::resource('subpages', 'SubpageController', $options)
            ->only(['show']);

        Route::resource('tales', 'TaleController', $options)
          ->only(['index', 'show']);
        Route::get('tales.atom', 'FeedController@talesAtom')->name($namePrefix . 'tales.atom');
        Route::get('tales.rss', 'FeedController@talesRss')->name($namePrefix . 'tales.rss');

        Route::get('talks.atom', 'FeedController@talksAtom')->name($namePrefix . 'talks.atom');
        Route::get('talks.rss', 'FeedController@talksRss')->name($namePrefix . 'talks.rss');

        // Contact
        Route::post('/contact', 'ContactController@sendMessage');

        // Old RSS URLs
        Route::get('/audio.rss', 'FeedController@talksRss');
        Route::get('/rss/audio.php', 'FeedController@talksRss');
        Route::get('/rss/news.php', 'FeedController@newsRss');
        Route::get('/rss/reflections.php', 'FeedController@reflectionsRss');

        // Diagnostic
        Route::get('/error', 'UtilController@error')->name($namePrefix . 'error');
        Route::get('/version', 'UtilController@version')->name($namePrefix . 'version');

        // Redirects
        Route::redirect(
            '/community/residents/{slug}',
            $lngPrefix . '/community/residents'
        );
        Route::redirect(
            '/audio',
            $lngPrefix . '/talks'
        );
        Route::get('/audio/{all}', 'LinkRedirectController@redirect')
            ->where('all', '(.*)');

        // Legacy
        Route::get('/', 'LegacyController@home');
        Route::get('/calendar', 'LegacyController@calendar');
        Route::get('/home', 'LegacyController@home');
        Route::get('/php/ajax.php', 'LegacyController@ajax');
        Route::get('/php/datatables.php', 'LegacyController@datatables');

        // Catch-all to be handled by SubpagePathController
        Route::get('{path}', 'SubpagePathController')
            ->name($namePrefix . 'subpages.path')->where('path', '.*');
    });
}
