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
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        \App\Models\Cart::observe(\App\Observers\CartObserver::class);
        \App\Models\SalesOrder::observe(\App\Observers\SalesOrderObserver::class);
        \Nova::style('custom-css', public_path('css/store.css'));
        
        // \DB::connection()->enableQueryLog();
        // \DB::listen(function($query) {
        //     \Log::info(
        //         $query->sql,
        //         $query->bindings,
        //         $query->time
        //     );
        // });
    }
}
