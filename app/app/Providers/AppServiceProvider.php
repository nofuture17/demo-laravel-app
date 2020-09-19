<?php

namespace App\Providers;

use App\ViewModel\Product\ProviderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\ServiceProvider;
use Tests\Fake\ViewModel\Product\Provider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProviderInterface::class, Provider::class);
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
