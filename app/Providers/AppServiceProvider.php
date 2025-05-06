<?php

namespace App\Providers;

use App\Repository\AgentRepository;
use App\Repository\AgentRepositoryInterface;
use App\Repository\ProviderRepository;
use App\Repository\ProviderRepositoryInterface;
use App\Repository\TripRepository;
use App\Repository\TripRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            AgentRepositoryInterface::class, AgentRepository::class,

        );
        $this->app->bind(
        providerRepositoryInterface::class, providerRepository::class,
        );
        $this->app->bind(
            TripRepositoryInterface::class, TripRepository::class,
        );


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
