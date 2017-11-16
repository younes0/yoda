let mix = require('laravel-mix');

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

mix.autoload({});

// scripts
mix
	.js('resources/assets/js/Yoda/Routes.js', 'public/js')
    .scripts([
        'resources/assets/js/vendor/vanilla/*.js',
        'resources/assets/js/vendor/jquery/*.js',
        'resources/assets/js/vendor/bootstrap/*.js',
    ], 'public/js/vendor.js')
	.scripts(['resources/assets/js/themes/adminlte.js'], 'public/js/adminlte.js')
;

// stylesheets
mix
    .styles([
        'resources/assets/less/themes/adminlte/AdminLTE.min.css',
        'resources/assets/less/themes/adminlte/_all-skins.min.css',
    ], 'public/css/adminlte.css')
    .styles('resources/assets/styles/vendor/*.css', 'public/css/vendor.css')
    .less('resources/assets/less/yeb/yeb.less', 'public/css')
    .less('resources/assets/less/yoda/yoda.less', 'public/css')
;

// cache busting
mix.version();
