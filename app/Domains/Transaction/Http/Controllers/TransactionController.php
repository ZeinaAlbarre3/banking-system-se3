<?php

namespace App\Domains\Transaction\Http\Controllers;

use App\Domains\Transaction\Data\TransactionCreateData;
use App\Domains\Transaction\Http\Requests\RejectTransactionRequest;
use App\Domains\Transaction\Http\Requests\StoreTransactionRequest;
use App\Domains\Transaction\Http\Resources\TransactionResource;
use App\Domains\Transaction\Models\Transaction;
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

    public function index(): JsonResponse
    {
        return self::Success(data: TransactionResource::collection(Transaction::all()));
    }

    public function show(Transaction $transaction): JsonResponse
    {
        return self::Success(data: new TransactionResource($transaction));
    }

    public function approve(Transaction $transaction): JsonResponse
    {
        $this->service->approve($transaction);
        return self::Success(msg: 'Transaction has been approved successfully');
    }

    public function reject(RejectTransactionRequest $request, Transaction $transaction): JsonResponse
    {
        $this->service->reject($transaction, $request->input('reason'));
        return self::Success(msg: 'Transaction has been rejected successfully');
    }
}
