<?php

namespace App\Providers;

use App\UrlEncoders\CodeGenerator;
use App\UrlEncoders\RandomBase62CodeGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CodeGenerator::class, function ($app) {
            return new RandomBase62CodeGenerator(9);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
