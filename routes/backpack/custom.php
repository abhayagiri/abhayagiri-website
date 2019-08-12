<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

// Admin Interface Routes
Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () {

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
            $controllerName = $modelClassName . 'CrudController';
            CRUD::resource($routeName, $controllerName);

            if (array_get($model, 'restore', true)) {
                $restorePath = $routeName . '/{id}/restore';
                $restoreName = 'crud.' . $routeName . '.restore';
                Route::get($restorePath, $controllerName . '@restore')
                    ->name($restoreName);
            }
        });
    }

    Route::get('dashboard', '\Backpack\Base\app\Http\Controllers\AdminController@dashboard');

    //Route::post('talks/search', 'Admin\TalkCrudController@searchAjax');

});

// Admin Authentication

Route::group([
     'prefix'     => config('backpack.base.route_prefix', 'admin'),
     'middleware' => ['web'],
     'namespace'  => 'App\Http\Controllers\Auth',
], function() {
    Route::get('', ['as' => 'index', 'uses' => 'LoginController@index']);
    Route::get('login', ['as' => 'login',
               'uses' => 'LoginController@showLoginForm']);
    Route::get('login', ['as' => 'login',
               'uses' => 'LoginController@showLoginForm']);
    Route::post('login', ['as' => 'login.post',
                'uses' => 'LoginController@login']);
    Route::get('logout', ['as' => 'logout',
               'uses' => 'LoginController@logout']);
    Route::post('logout', ['as' => 'logout.post',
                'uses' => 'LoginController@logout']);
    Route::get('login/google', ['as' => 'login.google',
               'uses' => 'LoginController@redirectToProvider']);
    Route::get('login/google/callback', ['as' => 'login.googleCallback',
               'uses' => 'LoginController@handleProviderCallback']);
    Route::get('login/dev-bypass', ['as' => 'login.devBypass',
               'uses' => 'LoginController@devBypass']);
});

// Defaults from backpack/base:
//
// Route::group([
//     'prefix'     => config('backpack.base.route_prefix', 'admin'),
//     'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
//     'namespace'  => 'App\Http\Controllers\Admin',
// ], function () { // custom admin routes
// }); // this should be the absolute last line of this file
