<?php

namespace App\Providers;

use App\Models\Product\OpenfoodfactsProvider;
use App\Models\Product\ProviderInterface as ModelProductProviderInterface;
use App\ViewModel\Product\Provider;
use App\ViewModel\Product\ProviderInterface as ViewModelProductProviderInterface;
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
        $this->app->bind(ViewModelProductProviderInterface::class, function ($app) {
            return new Provider($app->make(ModelProductProviderInterface::class));
        });
        $this->app->bind(ModelProductProviderInterface::class, OpenfoodfactsProvider::class);
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
