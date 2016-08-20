<?php

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

Route::post('/contact', 'ContactController@sendMessage');

Route::get('/php/ajax.php', function() {
    return legacyPage('ajax.php');
});

Route::any('/th', function() {
    setLegacyRequestParams('');
    return legacyPage('th-index.php');
});

Route::any('/th/{page}', function($page) {
    setLegacyRequestParams($page);
    return legacyPage('th-index.php');
})->where('page', '.*');

Route::any('{page}', function($page) {
    setLegacyRequestParams($page);
    return legacyPage('index.php');
})->where('page', '.*');

if (!function_exists('legacyPage')) {
    function legacyPage($legacyPhpFile)
    {
        define('LEGACY_ENTRY_PHP', $legacyPhpFile);
        return new \Illuminate\Http\Response('');
    }
}

if (!function_exists('setLegacyRequestParams')) {
    function setLegacyRequestParams($page)
    {
        $parts = preg_split('/\\//', trim($page, '/'), 3);
        for ($i = 0; $i < 3; $i++) {
            $key = ['_page', '_subpage', '_subsubpage'][$i];
            $_REQUEST[$key] = $_POST[$key] = $_GET[$key] = array_get($parts, $i, '');
        }
    }
}
