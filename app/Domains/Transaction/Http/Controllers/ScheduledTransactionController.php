<?php

namespace App\Domains\Transaction\Http\Controllers;

use App\Domains\Transaction\Data\ScheduledTransactionCreateData;
use App\Domains\Transaction\Http\Requests\StoreScheduledTransactionRequest;
use App\Domains\Transaction\Http\Resources\ScheduledTransactionResource;
use App\Domains\Transaction\Models\ScheduledTransaction;
use App\Domains\Transaction\Services\ScheduledTransactionService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ScheduledTransactionController extends Controller
{
    public function __construct(
        private readonly ScheduledTransactionService $service
    ) {}

    public function index(): JsonResponse
    {
        $items = $this->service->listMySchedules();

        return self::Success(data: ScheduledTransactionResource::collection($items));
    }

    public function store(StoreScheduledTransactionRequest $request): JsonResponse
    {
        $dto = ScheduledTransactionCreateData::from($request->validated());

        $scheduled = $this->service->create($dto);

        return self::Success(data: new ScheduledTransactionResource($scheduled), msg: 'Scheduled transaction created successfully.');
    }

    public function show(ScheduledTransaction $scheduledTransaction): JsonResponse
    {
        Gate::authorize('view', $scheduledTransaction);

        return self::Success(data: new ScheduledTransactionResource($scheduledTransaction));
    }

    public function toggle(ScheduledTransaction $scheduledTransaction): JsonResponse
    {
        Gate::authorize('update', $scheduledTransaction);

        $updated = $this->service->changeStatus($scheduledTransaction);

        return self::Success(data: new ScheduledTransactionResource($updated), msg: 'Scheduled transaction status updated.');
    }

    public function destroy(ScheduledTransaction $scheduledTransaction): JsonResponse
    {
        Gate::authorize('delete', $scheduledTransaction);

        $this->service->delete($scheduledTransaction);

        return self::Success(msg: 'Scheduled transaction deleted successfully.');
    }
}
