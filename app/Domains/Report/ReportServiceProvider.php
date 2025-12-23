<?php

namespace App\Domains\Report;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Domains\Report\Repositories\{
    ReportRepository,
    ReportRepositoryInterface
};

class ReportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ReportRepositoryInterface::class,
            ReportRepository::class
        );
    }

    public function boot(): void
    {
        Route::prefix('api/v1/admin')
            ->middleware('api')
            ->group(base_path('app/Domains/Report/Routes/admin.php'));
    }
}
