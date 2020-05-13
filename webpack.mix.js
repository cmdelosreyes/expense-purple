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

// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copy('resources/assets/img', 'public/images')
    .copy('resources/js/jquery-3.5.1.min.js', 'public/js/jquery.min.js')
    .copy('resources/js/adminlte-dt', 'public/js')
    .copy('resources/js/datepicker', 'public/js')
    .copy('resources/js/inputmask', 'public/js')
    .copy('resources/js/moment', 'public/js');
