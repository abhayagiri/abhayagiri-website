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

    Route::get('dashboard', '\Backpack\Base\app\Http\Controllers\AdminController@dashboard')
        ->name('backpack.dashboard');

    //Route::post('talks/search', 'Admin\TalkCrudController@searchAjax');

});

// Admin Authentication

Route::name('admin.')->group(function() {

    Route::group([
         'prefix'     => config('backpack.base.route_prefix', 'admin'),
         'middleware' => ['web'],
         'namespace'  => 'App\Http\Controllers\Auth',
    ], function() {

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
