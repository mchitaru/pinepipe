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

mix.js('resources/js/app.js', 'public/js')
   .combine(['resources/js/remote.js',
            'resources/js/avatar.js'], 'public/js/scripts.js')
   // .scripts('resources/js/remote.js', 'public/js/remote.js')
   // .scripts('resources/js/avatar.js', 'public/js/avatar.js')
   .sass('resources/sass/app.scss', 'public/css')
   .extract();

