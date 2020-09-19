<?php

namespace App\Providers;

use App\ViewModel\Product\ProductsProvider;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
        $this->app->bind(ProductsProvider::class, \Tests\Fake\Product\ProductsProvider::class);
        $this->app->bind(LengthAwarePaginator::class, \Illuminate\Pagination\LengthAwarePaginator::class);
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
