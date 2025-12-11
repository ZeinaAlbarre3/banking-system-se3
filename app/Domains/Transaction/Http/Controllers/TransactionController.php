<?php

namespace App\Domains\Transaction\Http\Controllers;

use App\Domains\Transaction\Data\TransactionCreateData;
use App\Domains\Transaction\Http\Requests\StoreTransactionRequest;
use App\Domains\Transaction\Http\Resources\TransactionResource;
use App\Domains\Transaction\Services\TransactionService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionService $service
    ) {}

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $data = TransactionCreateData::from($request->validated());

        $transaction = $this->service->create($data);

        return self::Success(
            data: new TransactionResource($transaction),
            msg: 'Transaction has been processed successfully'
        );
    }
}
