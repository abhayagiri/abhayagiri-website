<?php

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

Route::name('api.')->group(function () {
    Route::get(
        '/albums',
        'ApiController@getAlbums'
    )
        ->name('albums');
    Route::get(
        '/albums/{id}',
        'ApiController@getAlbum'
    )
        ->name('album');

    Route::get(
        '/authors',
        'ApiController@getAuthors'
    )
        ->name('authors');
    // Filters:
    //   minTalks=:integer
    //   maxTalks=:integer

    Route::get(
        '/authors/{id}',
        'ApiController@getAuthor'
    )
        ->name('author');

    Route::get(
        '/playlist-groups',
        'ApiController@getPlaylistGroups'
    )
        ->name('playlist-groups');
    Route::get(
        '/playlist-groups/{id}',
        'ApiController@getPlaylistGroup'
    )
        ->name('playlist-group');
    Route::get(
        '/playlist-groups/{id}/playlists',
        'ApiController@getPlaylists'
    )
        ->name('playlist-group.playlists');

    Route::get(
        '/playlists',
        'ApiController@getPlaylists'
    )
        ->name('playlists');
    Route::get(
        '/playlists/{id}',
        'ApiController@getPlaylist'
    )
        ->name('playlist');

    Route::get(
        '/redirects/{from}',
        'ApiController@getRedirect'
    )
        ->name('redirect')
       ->where('from', '.+');

    Route::get(
        '/subject-groups',
        'ApiController@getSubjectGroups'
    )
        ->name('subject-groups');
    Route::get(
        '/subject-groups/{id}',
        'ApiController@getSubjectGroup'
    )
        ->name('subject-group');
    Route::get(
        '/subject-groups/{id}/subjects',
        'ApiController@getSubjects'
    )
        ->name('subject-group.subjects');

    Route::get(
        'subjects',
        'ApiController@getSubjects'
    )
        ->name('subjects');
    Route::get(
        '/subjects/{id}',
        'ApiController@getSubject'
    )
        ->name('subject');

    Route::get(
        'talks',
        'ApiController@getTalks'
    )
        ->name('talks');
    // Filters:
    //   authorId=:id
    //   subjectId=:id
    //   searchText=:string
    //   startDate=:timestamp
    //   endDate=:timestamp
    //   page=:integer
    //   pageSize=:integer
    Route::get(
        'talks/latest',
        'ApiController@getTalksLatest'
    )
        ->name('talks.latest');
    Route::get(
        'talks/{id}',
        'ApiController@getTalk'
    )
        ->name('talk');

    Route::post(
        '/contact',
        'Api\ContactController@send'
    )
         ->name('contact.send');

    Route::get(
        'contact-preambles',
        'ApiController@getContactPreambles'
    )
         ->name('contact-preambles');

    Route::get(
        'contact-options',
        'ApiController@getContactOptions'
    )
         ->name('contact-options');
    Route::get(
        'contact-options/{slug}',
        'ApiController@getContactOption'
    )
         ->name('contact-option');

    Route::get(
        'search',
        'Api\SearchController'
    )
         ->name('search');
});
