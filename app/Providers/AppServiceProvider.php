<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
// require_once("../vendor/phpoffice/phppresentation/src/PhpPresentation/Autoloader.php");
// require_once("../vendor/phpoffice/common/src/Common/Autoloader.php");

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \PhpOffice\PhpPresentation\Autoloader::register();
        \PhpOffice\Common\Autoloader::register();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Paginator::useBootstrap();
    }
}
