const mix = require('laravel-mix');
const path = require('path');

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

mix.extend('translations', new class {
    webpackRules() {
        return {
            test: path.resolve(__dirname, 'lang/index.js'),
            loader: '@kirschbaum-development/laravel-translations-loader/php',
            options: {
                parameters: "$1",
            }
        }
    }
});

mix.translations();

mix.js('resources/js/admin.js', 'public/js');
mix.js('resources/js/app.js', 'public/js').vue();
mix.js('resources/js/react/app.js', 'public/js/react.js').react();

mix.sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/admin.scss', 'public/css')
    .sass('resources/sass/react/app.scss', 'public/css/react.css');

// Extract vendor js to js/vendor.js to help with client caching
mix.extract([
    'algoliasearch-helper', // dep
    'algoliasearch',
    'axios',
    'bootstrap',
    'hogan.js', // dep
    'instantsearch.js',
    'jquery',
    'lodash',
    'popper.js',
    'preact-compat', // dep
    'preact-context', // dep
    'preact', // dep
    'prop-types', // dep
    'react-is', // dep
    'vue',
    'vue-i18n',
    'vue-instantsearch',
    'vue-recaptcha'
]);

// Use versioning (cache busting) in production
if (mix.inProduction()) {
    mix.version();
}
