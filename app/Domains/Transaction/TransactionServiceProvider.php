<?php

namespace App\Domains\Transaction;


use App\Domains\Transaction\Repositories\TransactionRepository;
use App\Domains\Transaction\Repositories\TransactionRepositoryInterface;
use App\Domains\Transaction\Chains\EnsureRelatedAccountProvidedForTransferChain;
use App\Domains\Transaction\Chains\EnsureSameOwnerForTransferChain;
use App\Domains\Transaction\Chains\EnsureSufficientBalanceChain;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;


class TransactionServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            TransactionRepositoryInterface::class,
            TransactionRepository::class
        );

        $this->app->singleton(EnsureRelatedAccountProvidedForTransferChain::class, fn () => new EnsureRelatedAccountProvidedForTransferChain());
        $this->app->singleton(EnsureSameOwnerForTransferChain::class, fn () => new EnsureSameOwnerForTransferChain());
        $this->app->singleton(EnsureSufficientBalanceChain::class, fn () => new EnsureSufficientBalanceChain());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        $this->registerRoutes();

        //Gate::policy(Transaction::class, TransactionPolicy::class);

    }

    /**
     * Register the routes for the domain.
     */
    protected function registerRoutes(): void
    {
        Route::prefix('api/v1/transactions')
             ->middleware('api')
             ->group(__DIR__.'/Routes/api.php');
    }
}
