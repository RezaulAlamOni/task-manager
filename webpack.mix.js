let mix = require('laravel-mix');
let exec = require('child_process').exec;
let path = require('path');

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
//source paths
var resourcesAssets = 'resources/';
// var sparkpath       = "spark/install-stubs/public/"
var srcCss = resourcesAssets + 'css/';
var nodes = 'node_modules/'

//====== destination path configuration ======

var dest = 'public/';
var destFonts = dest + 'fonts/';
var destCss = dest + 'css/';
var destVendors = dest + 'vendors/';


// =====paths for vendors
var paths = {
    'select2': nodes + 'select2/dist/',
    'select2BootstrapTheme': nodes + 'select2-bootstrap-theme/dist/'
};

// ========copy images=====
mix.copy(resourcesAssets + 'img', 'public/img');
// mix.copy(sparkpath + 'img/', 'public/img');


// ========copy fonts=====
mix.copy(resourcesAssets + 'fonts', 'public/fonts');

// ========copy css=====
mix.copy(resourcesAssets + 'css', "public/css");


//select2
mix.copy(paths.select2 + 'css/select2.min.css', destVendors + 'select2/css');
mix.copy(paths.select2 + 'js/select2.js', destVendors + 'select2/js');
mix.copy(paths.select2BootstrapTheme + 'select2-bootstrap.css', destVendors + 'select2/css');

// =======mix styles======
mix.sass('resources/sass/bootstrap/bootstrap.scss', 'public/css');
mix.sass(resourcesAssets + 'sass/custom.scss', 'public/css');
mix.sass(resourcesAssets + 'sass/light_custom.scss', 'public/css');


mix.sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/board_view.scss', 'public/css')
    .sass('resources/sass/tree_view.scss', 'public/css')
    .js('resources/js/app.js', 'public/js')
    .copy('node_modules/sweetalert/dist/sweetalert.min.js', 'public/js/sweetalert.min.js')
    .sass('resources/sass/app-rtl.scss', 'public/css')

    .then(() => {
        exec('node_modules/rtlcss/bin/rtlcss.js public/css/app-rtl.css ./public/css/app-rtl.css');
    })
    .version(['public/css/app-rtl.css'])
    .webpackConfig({
        resolve: {
            modules: [
                path.resolve(__dirname, 'vendor/laravel/spark-aurelius/resources/assets/js'),
                'node_modules'
            ],
            alias: {
                'vue$': mix.inProduction() ? 'vue/dist/vue.min' : 'vue/dist/vue.js'
            }
        }
    });
