<?php

namespace App\Domains\Transaction\Services;

use App\Domains\Account\Models\Account;
use App\Domains\Account\Repositories\AccountRepositoryInterface;
use App\Domains\Notification\Enums\AccountActivityTypeEnum;
use App\Domains\Notification\Models\AccountActivity;
use App\Domains\Transaction\Approval\TransactionApprovalChainFactory;
use App\Domains\Transaction\Data\TransactionApprovalData;
use App\Domains\Transaction\Data\TransactionCreateData;
use App\Domains\Transaction\Enums\TransactionStatusEnum;
use App\Domains\Transaction\Enums\TransactionTypeEnum;
use App\Domains\Transaction\Exceptions\TransactionRuleException;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\Transaction\Repositories\TransactionRepositoryInterface;
use App\Domains\Transaction\Strategies\TransactionStrategyFactory;
use App\Domains\Transaction\Chains\TransactionValidationChainFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function __construct(
        private readonly AccountRepositoryInterface        $accounts,
        private readonly TransactionRepositoryInterface    $transactions,
        private readonly TransactionStrategyFactory        $strategyFactory,
        private readonly TransactionValidationChainFactory $validationChainFactory,
        private readonly TransactionApprovalChainFactory   $approvalChainFactory,
        private readonly AuditLoggerService                $audit,
    ) {}

    public function create(TransactionCreateData $data): Transaction
    {
        [$account, $related] = $this->resolveAccounts($data);

        $this->validate($account, $data, $related);

        return DB::transaction(function () use ($account, $related, $data) {

            $transaction = $this->createPendingTransaction($account, $related, $data);

            $this->audit->auditCreated($transaction, $data);

            $this->runApprovalChain($transaction, $data);

            if ($this->isPending($transaction)) {
                $this->audit->auditPending($transaction);
                return $transaction;
            }

            $this->applyStrategy($account, $related, $data);

            $this->markCompleted($transaction);

            $this->audit->auditCompleted($transaction);

            $this->createActivity($account,$data);

            return $transaction;
        });
    }

    private function resolveAccounts(TransactionCreateData $data): array
    {
        $account = $this->accounts->findByReferenceOrFail($data->account_reference);

        $related = null;
        if ($data->related_account_reference !== null) {
            $related = $this->accounts->findByReferenceOrFail($data->related_account_reference);
        }

        return [$account, $related];
    }

    private function validate(Account $account, TransactionCreateData $data, ?Account $related): void
    {
        $this->validationChainFactory->make()->handle($account, $data, $related);
    }

    private function createPendingTransaction(Account $account, ?Account $related, TransactionCreateData $data): Transaction
    {
        return $this->transactions->create([
            'reference_number'   => uniqid('TXN-'),
            'account_id'         => $account->id,
            'related_account_id' => $related?->id,
            'type'               => $data->type->value,
            'status'             => TransactionStatusEnum::PENDING->value,
            'amount'             => $data->amount,
            'currency'           => $data->currency ?? 'USD',
            'metadata'           => $data->metadata ?? [],
            'created_by'         => Auth::id(),
            'approved_by'        => null,
            'processed_at'       => null,
        ]);
    }

    private function runApprovalChain(Transaction $transaction, TransactionCreateData $data): void
    {
        $approvalData = new TransactionApprovalData(
            transaction: $transaction,
            data: $data
        );

        $this->approvalChainFactory->make()->handle($approvalData);

        $transaction->refresh();
    }
    private function isPending(Transaction $transaction): bool
    {
        return $transaction->status === TransactionStatusEnum::PENDING->value;
    }

    private function applyStrategy(Account $account, ?Account $related, TransactionCreateData $data): void
    {
        $strategy = $this->strategyFactory->forType($data->type);
        $strategy->apply($account, $data, $related);
    }

    private function markCompleted(Transaction $transaction): void
    {
        $this->transactions->update($transaction, [
            'status'       => TransactionStatusEnum::COMPLETED->value,
            'approved_by'  => Auth::id(),
            'processed_at' => now(),
        ]);
    }


    public function approve(Transaction $transaction): Transaction
    {
        return DB::transaction(function () use ($transaction) {

            $account = $transaction->account; // relationship
            $related = $transaction->relatedAccount; // relationship nullable

            $data = TransactionCreateData::from([
                'type' => TransactionTypeEnum::from($transaction->type),
                'account_reference' => $account->reference_number,
                'amount' => (float) $transaction->amount,
                'related_account_reference' => $related?->reference_number,
                'currency' => $transaction->currency,
                'metadata' => $transaction->metadata,
            ]);

            $strategy = $this->strategyFactory->forType($data->type);
            $strategy->apply($account, $data, $related);

            $this->transactions->update($transaction, [
                'status'       => TransactionStatusEnum::APPROVED->value,
                'approved_by'  => Auth::id(),
                'processed_at' => now(),
            ]);

            $this->audit->auditApproved($transaction);

            $this->createActivity($account,$data);

            return $transaction->refresh();
        });
    }

    public function reject(Transaction $transaction, ?string $reason = null): Transaction
    {
        $meta = $transaction->metadata ?? [];
        if ($reason) {
            $meta['rejection_reason'] = $reason;
        }

        $this->transactions->update($transaction, [
            'status'      => TransactionStatusEnum::REJECTED->value,
            'approved_by' => Auth::id(),
            'metadata'    => $meta,
        ]);

        $this->audit->auditRejected($transaction,$reason);

        return $transaction->refresh();
    }

    public function createActivity(Account $account,TransactionCreateData $data): void
    {
        $this->transactions->createActivity([
                'account_id'      => $account->id,
                'user_id'         => Auth::id(),
                'type'            => AccountActivityTypeEnum::BALANCE_CHANGE->value,
                'amount'          => $data->amount,
                'balance_before' => $account->getOriginal('balance'),
                'balance_after'  => $account->balance,
        ]);
    }


}
