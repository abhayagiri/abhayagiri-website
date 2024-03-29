<?php

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
            ->name($namePrefix . 'books.cart.edit');
        Route::post('books/request', 'BookCartController@sendRequest')
            ->name($namePrefix . 'books.cart.submit');

        Route::resource('books', 'BookController', $options)
            ->only(['index', 'show']);

        Route::get('calendar', 'CalendarController@index')
            ->name($namePrefix . 'calendar.index');
        Route::get('calendar/{year}/{month}/{day}', 'CalendarController@day')
            ->name($namePrefix . 'calendar.day')
            ->where('year', '[0-9]+')
            ->where('month', '[0-9]+')
            ->where('day', '[0-9]+');

        Route::get('calendar/{year}/{month}', 'CalendarController@month')
            ->name($namePrefix . 'calendar.month')
            ->where('year', '[0-9]+')
            ->where('month', '[0-9]+');

        Route::get('contact', 'ContactController@index')
            ->name($namePrefix . 'contact.index');
        Route::get('contact/{contactOption}', 'ContactController@show')
            ->name($namePrefix . 'contact.show');
        Route::post('contact/{contactOption}', 'SendContactMessageController')
            ->name($namePrefix . 'contact.send-message');

        Route::resource('gallery', 'GalleryController', $options)
            ->parameters(['gallery' => 'album'])
            ->only(['index', 'show']);

        Route::get('/', 'HomeController@index')
            ->name($namePrefix . 'home.index');
        Route::redirect('/home', $lngPrefix . '/');

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
        foreach (['author', 'book', 'news', 'reflection', 'resident', 'tale', 'talk'] as $type) {
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

        // React catch-all routes
        // These need to be after the main routes
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

        // Catch-all to be handled by SubpagePathController
        Route::get('{path}', 'SubpagePathController')
            ->name($namePrefix . 'subpages.path')->where('path', '.*');
    });
}
