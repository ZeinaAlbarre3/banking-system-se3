<?php

namespace App\Domains\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Dashboard\Services\DashboardService;
use App\Domains\Dashboard\Resources\DashboardResource;
use Illuminate\Auth\Access\AuthorizationException;

class AdminDashboardController extends Controller
{

    public function index(DashboardService $service)
    {

        return self::Success(data: new DashboardResource(
            $service->getDashboardData()));



    }
}
