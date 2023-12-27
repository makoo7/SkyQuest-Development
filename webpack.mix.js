const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/app.js', 'public/js').postCss('resources/css/app.css', 'public/css', [
//     require('tailwindcss'),
//     require('autoprefixer'),
// ]);

mix.styles([
    'public/assets/frontend/css/bootstrap.min.css',
    'public/assets/frontend/css/slick.min.css',
    'public/assets/frontend/css/slick-theme.min.css',
    'public/assets/frontend/css/all.min.css',
    'public/assets/frontend/css/toastr.min.css',
    'public/assets/frontend/css/jquery.datetimepicker.css',
    'public/assets/frontend/css/jquery-impromptu.css',
    'public/assets/frontend/css/select2.min.css',
    'public/assets/frontend/css/style.css',
    'public/assets/frontend/css/media.css',
], 'public/assets/frontend/css/all.css');

mix.scripts([
    'public/assets/frontend/js/jquery-3.6.0.min.js',
    'public/assets/frontend/js/jquery.validate.min.js',
    'public/assets/frontend/js/additional-methods.min.js',
    'public/assets/frontend/js/jquery-ui.min.js',
    'public/assets/frontend/js/bootstrap.bundle.min.js',
    'public/assets/frontend/js/toastr.min.js',
    'public/assets/frontend/js/slick.min.js',
    'public/assets/frontend/js/jquery-impromptu.js',
    'public/assets/frontend/js/share.js',
    'public/assets/frontend/js/jquery.datetimepicker.full.js',
    'public/assets/frontend/js/charts-loader.js',
    'public/assets/frontend/js/select2.min.js',
    /*'public/assets/frontend/js/pages/aboutus.js',
    'public/assets/frontend/js/pages/jobapplication.js',
    'public/assets/frontend/js/pages/insights-detail.js',
    'public/assets/frontend/js/pages/casestudies.js',
    'public/assets/frontend/js/pages/bookappointment.js',
    'public/assets/frontend/js/pages/index.js',
    'public/assets/frontend/js/pages/search.js',
    'public/assets/frontend/js/pages/insights.js',
    'public/assets/frontend/js/pages/buynow.js',
    'public/assets/frontend/js/pages/reports.js',
    'public/assets/frontend/js/pages/samplerequest.js',
    'public/assets/frontend/js/pages/speakwithanalyst.js',
    'public/assets/frontend/js/pages/subscribenow.js',*/
], 'public/assets/frontend/js/all.js');