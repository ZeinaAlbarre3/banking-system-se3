<?php

namespace App\Domains\Transaction\Models;

use App\Domains\Account\Models\Account;
use App\Domains\Auth\Models\User;
use App\Traits\HasUniqueCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledTransaction extends Model
{
    protected $table = 'scheduled_transactions';

    use HasUniqueCode;
    protected $fillable = [
        'reference_number',
        'user_id',
        'account_id',
        'related_account_id',
        'type',
        'amount',
        'currency',
        'metadata',
        'frequency',
        'day_of_week',
        'day_of_month',
        'time_of_day',
        'timezone',
        'status',
        'next_run_at',
        'last_run_at',
        'runs_count',
        'last_run_key',
        'last_error',
    ];

    protected $casts = [
        'metadata' => 'array',
        'next_run_at' => 'datetime',
        'last_run_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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
        return 'SCH-';
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
