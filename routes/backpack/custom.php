<?php

// --------------------------
// Admin Authentication
// --------------------------

Route::name('admin.')->group(function () {
    Route::group([
        'prefix' => config('backpack.base.route_prefix', 'admin'),
        'middleware' => ['web'],
        'namespace' => 'App\Http\Controllers\Auth',
    ], function () {
        Route::get('', 'LoginController@index')
            ->name('index');

        Route::get('login', 'LoginController@showLoginForm')
            ->name('login');
        Route::post('login', 'LoginController@login')
            ->name('login.post');

        Route::get('login/google', 'LoginController@redirectToProvider')
            ->name('login.google');
        Route::get('login/google/callback', 'LoginController@handleProviderCallback')
            ->name('login.google.callback');

        Route::get('login/dev-bypass', 'LoginController@devBypass')
            ->name('login.dev-bypass');

        Route::match(['get', 'post'], 'logout', 'LoginController@logout')
            ->name('logout');
    });
});

//Route::get('dashboard',
//           '\Backpack\CRUD\app\Http\Controllers\AdminController@dashboard')
//    ->name('backpack.dashboard');

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.
//
// Note: most of these routes are defined automagically using configuration
// from config/admin.php.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::name('admin.')->group(function () {
        foreach (config('admin.models') as $model) {
            $groupOptions = array_get($model, 'super_admin') ?
                            [ 'middleware' => 'super_admin' ] : [];

            Route::group($groupOptions, function () use ($model) {
                $routeName = $model['name'];
                $modelClassName = studly_case(str_singular($routeName));
                $controllerName = $modelClassName . 'CrudController';

                Route::crud($routeName, $controllerName);

                if (array_get($model, 'restore', true)) {
                    Route::get(
                        "${routeName}/{id}/restore",
                        "${controllerName}@restore"
                    )
                        ->name("${routeName}.restore");
                }
            });
        }
    });

    Route::group(['prefix' => 'api', 'namespace' => 'Api', 'as' => 'admin.api.'], function () {
        Route::post('validate-url', 'ValidateUrlController')->name('validate-url');
        Route::post('render-markdown', 'RenderMarkdownController')->name('render-markdown');
    });
}); // this should be the absolute last line of this file
