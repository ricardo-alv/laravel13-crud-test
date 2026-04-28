<?php

namespace App\Providers;

use App\Interfaces\SocialMediaServiceInterface;
use App\Services\LinkedinService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SocialMediaServiceInterface::class, function () {
            return new LinkedinService();
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
