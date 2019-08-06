const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// Needed for this offshoot mix directory
mix.setPublicPath('../public');

mix.js('resources/js/app.js', '../public/mix/js')
    .sass('resources/sass/app.scss', '../public/mix/css');

mix.extract(['axios', 'bootstrap', 'jquery', 'popper.js']);

// Use versioning (cache busting) in production
if (mix.inProduction()) {
    mix.version();
}
