<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // When migrating the database on MySQL below v5.7.7 versions
        // Following error occurs:
        // SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long;
        // max key length is 767 bytes.

        // Laravel 5.4 made a change to the default database character set,
        // and it’s now utf8mb4 which includes support for storing emojis.
        // This only affects new applications and as long as you are running
        // MySQL v5.7.7 and higher you do not need to do anything.
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
