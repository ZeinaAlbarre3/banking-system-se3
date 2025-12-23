<?php

namespace App\Domains\Notification\Models;

use App\Domains\Account\Models\Account;
use App\Domains\Auth\Models\User;
use App\Domains\Notification\Observers\AccountActivityObserver;
use App\Traits\HasUniqueCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountActivity extends Model
{
    use HasFactory, HasUniqueCode;

    protected $table = 'account_activities';

    protected $fillable = [
        'reference_number',
        'account_id',
        'user_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::observe(AccountActivityObserver::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
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
