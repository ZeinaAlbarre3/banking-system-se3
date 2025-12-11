<?php

namespace App\Domains\Transaction;

use App\Domains\Account\Composites\AccountTreeBuilder;
use App\Domains\Account\Models\Account;
use App\Domains\Account\Policies\AccountPolicy;
use App\Domains\Account\Repositories\AccountRepository;
use App\Domains\Account\Repositories\AccountRepositoryInterface;
use App\Domains\Account\Rules\EnsureAccountCanBeClosedRule;
use App\Domains\Account\Rules\EnsureNotSelfParentRule;
use App\Domains\Account\Rules\EnsureSameOwnerRule;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\Transaction\Repositories\TransactionRepository;
use App\Domains\Transaction\Repositories\TransactionRepositoryInterface;
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
