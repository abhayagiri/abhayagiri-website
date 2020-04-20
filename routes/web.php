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

// Media routes
Route::get('/image-cache/{path}', 'ImageCacheController@image')
    ->name('image-cache')->where('path', '.*');

foreach (['th', 'en'] as $lng) {
    $options = $lng === 'en' ? [] : ['prefix' => 'th', 'middleware' => 'thai'];
    Route::group($options, function () use ($lng) {
        $lngPrefix = $lng === 'en' ? '' : '/th';
        $namePrefix = $lng === 'en' ? '' : 'th.';
        $options = $lng === 'en' ? [] : ['as' => 'th']; // for resource(...)

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

        Route::resource('books', 'BookController', $options)
            ->only(['index', 'show']);

        Route::get('contact', 'ContactController@index')
            ->name($namePrefix . 'contact.index');
        Route::post('contact', 'ContactController@sendMessage');

        Route::get('gallery', 'GalleryController@index')
            ->name($namePrefix . 'gallery.index');

        Route::resource('reflections', 'ReflectionController', $options)
            ->only(['index', 'show']);

        Route::resource('news', 'NewsController', $options)
            ->only(['index', 'show']);

        Route::resource('subpages', 'SubpageController', $options)
            ->only(['show']);

        Route::resource('tales', 'TaleController', $options)
          ->only(['index', 'show']);

        Route::get('talks', 'TalkController@index')
            ->name($namePrefix . 'talks.index');
        // This is needed for HasPath trait in Models/Talk.
        Route::get('talks/{id}', 'TalkController@index')
            ->name($namePrefix . 'talks.show');
        Route::get('talks/{talk}/audio.{format}', 'TalkController@audio')
            ->name($namePrefix . 'talks.audio');

        // Image routes
        foreach (['author', 'book', 'news', 'reflection', 'tale', 'talk'] as $type) {
            $plural = Str::plural($type);
            $controller = Str::title($type) . 'Controller';
            Route::get(
                "$plural/{" . $type . "}/image/{preset}.{format}",
                "$controller@image"
            )->name("$namePrefix$plural.image");
        }

        // Atom/RSS routes
        foreach (['news', 'reflection', 'tale', 'talk'] as $type) {
            $plural = Str::plural($type);
            Route::get("$plural.atom", "FeedController@${plural}Atom")
                ->name("$namePrefix$plural.atom");
            Route::get("$plural.rss", "FeedController@${plural}Rss")
                ->name("$namePrefix$plural.rss");
        }

        // new proxy catch-all routes
        // These need to be after the main routes
        Route::get('contact/{all}', 'ContactController@index')
            ->where('all', '(.*)')->name($namePrefix . 'contact.catchall');
        Route::get('gallery/{all}', 'GalleryController@index')
            ->where('all', '(.*)')->name($namePrefix . 'gallery.catchall');
        Route::get('talks/{all}', 'TalkController@index')
            ->where('all', '(.*)')->name($namePrefix . 'talks.catchall');

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
