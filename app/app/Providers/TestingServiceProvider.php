<?php


namespace App\Providers;


use App\ViewModel\Product\ProductsProvider;
use Illuminate\Support\ServiceProvider;

class TestingServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->env != 'testing') {
            return;
        }

        $this->app->bind(ProductsProvider::class, \Tests\Fake\Product\ProductsProvider::class);
    }

    public function boot()
    {
        //
    }
}
