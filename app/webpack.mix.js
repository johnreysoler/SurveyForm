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

mix.js('resources/js/app.js', 'public/js').vue()
    .styles([
        //Metronic Template
        'public/css/global/plugins.bundle.css',
        'public/css/global/prismjs.bundle.css',
        'public/css/pages/login-2.css',
        'public/css/style.bundle.css'
    ], 'public/css/app.css')
    .js([
        //Metronic Template
        // 'public/js/plugins/global/plugins.bundle.js',
        // 'public/js/plugins/global/jquery.min.js',
        // // 'public/js/plugins/global/login-general.js',
        //  'public/js/plugins/global/prismjs.bundle.js',
        // 'public/js/plugins/global/scripts.bundle.js',
       

    ], 'public/js/app.js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();
