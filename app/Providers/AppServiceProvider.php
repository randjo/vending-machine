<?php

namespace App\Providers;

use App\Domain\Vending\DisplayRepositoryInterface;
use App\Domain\Vending\WalletRepositoryInterface;
use App\Repositories\DatabaseDisplayRepository;
use App\Repositories\DatabaseWalletRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            WalletRepositoryInterface::class,
            DatabaseWalletRepository::class
        );

        $this->app->bind(
            DisplayRepositoryInterface::class,
            DatabaseDisplayRepository::class
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
