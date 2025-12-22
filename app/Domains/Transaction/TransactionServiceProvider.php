<?php

namespace App\Domains\Transaction;


use App\Domains\Transaction\Chains\EnsureRelatedAccountProvidedForTransferChain;
use App\Domains\Transaction\Chains\EnsureSameOwnerForTransferChain;
use App\Domains\Transaction\Chains\EnsureSufficientBalanceChain;
use App\Domains\Transaction\Console\Commands\RunScheduledTransactions;
use App\Domains\Transaction\Policies\ScheduledTransactionPolicy;
use App\Domains\Transaction\Repositories\Transaction\TransactionRepository;
use App\Domains\Transaction\Repositories\Transaction\TransactionRepositoryInterface;
use App\Domains\Transaction\Repositories\TransactionSchedule\ScheduledTransactionRepository;
use App\Domains\Transaction\Repositories\TransactionSchedule\ScheduledTransactionRepositoryInterface;
use Illuminate\Support\Facades\Gate;
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

        $this->app->bind(
            ScheduledTransactionRepositoryInterface::class,
            ScheduledTransactionRepository::class
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

        Gate::policy(ScheduledTransactionPolicy::class, ScheduledTransactionPolicy::class);

        if ($this->app->runningInConsole()) {
            $this->registerConsoleRoutes();
        }
    }

    /**
     * Register the routes for the domain.
     */
    protected function registerRoutes(): void
    {
        Route::prefix('api/v1')
             ->middleware('api')
             ->group(__DIR__.'/Routes/api.php');
    }

    /**
     * Register the console routes (commands & schedule) for this domain.
     */
    protected function registerConsoleRoutes(): void
    {
        $this->commands([
            RunScheduledTransactions::class,
        ]);

        $this->loadRoutesFrom(__DIR__.'/Routes/console.php');
    }
}
