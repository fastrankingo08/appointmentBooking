<?php

namespace App\Providers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        //
        if (env('APP_DEBUG')) {
            DB::listen(function ($query) {
                Log::debug("SQL: {$query->sql}");
                Log::debug("Bindings: " . json_encode($query->bindings));
                Log::debug("Time: {$query->time} ms");
            });
        }
    }
}
