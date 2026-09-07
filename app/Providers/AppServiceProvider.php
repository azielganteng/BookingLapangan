<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache; 
use App\Models\Lapangan;
use App\Models\Booking;

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
        if (
            request()->header('x-forwarded-proto') === 'https'
            || request()->isSecure()
            || str_contains(request()->getHttpHost(), 'trycloudflare.com')
            || config('app.env') === 'production'
        ) {
            URL::forceScheme('https');
        }
    }
}


