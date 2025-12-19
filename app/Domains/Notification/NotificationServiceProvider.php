<?php

namespace App\Domains\Notification;


use App\Domains\Notification\Models\AccountActivity;
use App\Domains\Notification\Models\Notification;
use App\Domains\Notification\Observers\AccountActivityObserver;
use App\Domains\Notification\Policies\NotificationPolicy;
use App\Domains\Notification\Repositories\NotificationRepository;
use App\Domains\Notification\Repositories\NotificationRepositoryInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;


class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            NotificationRepositoryInterface::class,
            NotificationRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
        $this->registerRoutes();
        AccountActivity::observe(AccountActivityObserver::class);
        Gate::policy(Notification::class, NotificationPolicy::class);


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
}
