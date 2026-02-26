<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') !== 'local' || request()->server('HTTP_X_FORWARDED_PROTO') == 'https' || str_contains(request()->getHost(), 'trycloudflare.com') || str_contains(request()->getHost(), 'loca.lt')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
