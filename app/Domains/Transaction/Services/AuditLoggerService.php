<?php

namespace App\Domains\Transaction\Services;

use App\Domains\Transaction\Data\TransactionCreateData;
use App\Domains\Transaction\Models\AuditLog;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLoggerService
{
    public function log(?int $actorId, string $action, $auditable, array $meta = []): void
    {
        AuditLog::query()->create([
            'actor_id' => $actorId,
            'action' => $action,
            'auditable_type' => get_class($auditable),
            'auditable_id' => $auditable->getKey(),
            'meta' => $meta,
            'ip' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    public function auditCreated(Transaction $transaction, TransactionCreateData $data): void
    {
        $this->log(Auth::id(), 'transaction.created', $transaction, [
            'type' => $data->type->value,
            'amount' => $data->amount,
        ]);
    }

    public function auditPending(Transaction $transaction): void
    {
        $this->log(Auth::id(), 'transaction.pending', $transaction);
    }

    public function auditCompleted(Transaction $transaction): void
    {
        $this->log(Auth::id(), 'transaction.completed', $transaction);
    }

    public function auditApproved(Transaction $transaction): void
    {
        $this->log(Auth::id(), 'transaction.approved', $transaction);
    }

    public function auditRejected(Transaction $transaction, string $reason): void
    {
        $this->log(Auth::id(), 'transaction.rejected', $transaction,[
            'reason' => $reason,
        ]);
    }
}
