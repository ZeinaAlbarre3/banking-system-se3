<?php

namespace App\Domains\Transaction\Models;

use App\Domains\Account\Models\Account;
use App\Traits\HasUniqueCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasUniqueCode;
    protected $fillable = [
        'reference_number',
        'account_id',
        'related_account_id',
        'type',
        'status',
        'amount',
        'currency',
        'metadata',
        'created_by',
        'approved_by',
        'processed_at',
    ];

    protected $casts = [
        'metadata'      => 'array',
        'processed_at'  => 'datetime',
        'amount'        => 'float',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function relatedAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'related_account_id');
    }

    protected function getCodeColumn(): string
    {
        return 'reference_number';
    }

    protected function getCodePrefix(): string
    {
        return 'TXN-';
    }

    protected function getCodePadding(): int
    {
        return 6;
    }

    public function getRouteKeyName(): string
    {
        return 'reference_number';
    }
}
