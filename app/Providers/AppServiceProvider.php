<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit; // 1. IMPORT INI
use Illuminate\Http\Request;             // 2. IMPORT INI
use Illuminate\Support\Facades\RateLimiter; // 3. IMPORT INI
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
        // 4. TAMBAHKAN KODE INI
        // Ini mendefinisikan apa itu limiter bernama 'api'
        RateLimiter::for('api', function (Request $request) {
            // Batasi 60 request per menit.
            // Dihitung berdasarkan ID user (kalau login) atau IP Address (kalau tamu).
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}