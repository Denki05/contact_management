<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // === FIX UNTUK ERROR DOMPDF "Cannot resolve public path" ===
        // Memaksa resolusi public_path() sebelum Dompdf ServiceProvider diinisialisasi.
        if (isset($_SERVER['SCRIPT_FILENAME'])) {
            $publicPath = dirname($_SERVER['SCRIPT_FILENAME']);
            $this->app->instance('path.public', $publicPath);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
