<?php

namespace App\Domains\Account;

use App\Domains\Account\Composites\AccountTreeBuilder;
use App\Domains\Account\Models\Account;
use App\Domains\Account\Policies\AccountPolicy;
use App\Domains\Account\Repositories\AccountRepository;
use App\Domains\Account\Repositories\AccountRepositoryInterface;
use App\Domains\Account\Rules\EnsureNotSelfParentRule;
use App\Domains\Account\Rules\EnsureSameOwnerRule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;


class AccountServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            AccountRepositoryInterface::class,
            AccountRepository::class
        );

        $this->app->singleton(AccountTreeBuilder::class, fn () => new AccountTreeBuilder());
        $this->app->singleton(EnsureSameOwnerRule::class, fn () => new EnsureSameOwnerRule());
        $this->app->singleton(EnsureNotSelfParentRule::class, fn () => new EnsureNotSelfParentRule());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        $this->registerRoutes();

        Gate::policy(Account::class, AccountPolicy::class);

    }

    /**
     * Register the routes for the domain.
     */
    protected function registerRoutes(): void
    {
        Route::prefix('api/v1/accountants')
             ->middleware('api')
             ->group(__DIR__.'/Routes/api.php');
    }
}
