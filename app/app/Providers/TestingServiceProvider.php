<?php


namespace App\Providers;


use App\ViewModel\Product\ProviderInterface;
use Illuminate\Support\ServiceProvider;
use Tests\Fake\ViewModel\Product\Provider;

class TestingServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->env != 'testing') {
            return;
        }

        $this->app->bind(ProviderInterface::class, Provider::class);
    }

    public function boot()
    {
        //
    }
}
