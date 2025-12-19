<?php

namespace App\Domains\Account\Models;

use App\Domains\Account\Enums\AccountStateEnum;
use App\Domains\Account\Enums\AccountTypeEnum;
use App\Domains\Account\ValueObjects\Money;
use App\Domains\Auth\Models\User;
use App\Domains\Transaction\Models\Transaction;
use App\Traits\HasUniqueCode;
use Database\Factories\AccountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasUniqueCode,HasFactory;

    protected $table = 'accounts';
    protected $fillable = [
        'reference_number',
        'user_id',
        'name',
        'type',
        'parent_id',
        'state',
        'balance',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'balance'  => 'decimal:2',
    ];

    protected static function newFactory()
    {
        return AccountFactory::new();
    }

    public function getTypeEnum(): ?AccountTypeEnum
    {
        return $this->type
            ? AccountTypeEnum::from($this->type)
            : null;
    }

    public function getStateEnum(): ?AccountStateEnum
    {
        return $this->state
            ? AccountStateEnum::from($this->state)
            : null;
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }

    public function incomingTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'related_account_id');
    }

    public function allTransactions()
    {
        return Transaction::query()->where('account_id', $this->id)->orWhere('related_account_id', $this->id);
    }

    public function isActive(): bool
    {
        return $this->state === AccountStateEnum::ACTIVE->value;
    }

    public function canBeClosed(): bool
    {
        return (float) $this->getRawOriginal('balance') === 0.0;
    }

    public function balanceValue(): Money
    {
        return new Money((float) $this->balance);
    }

    protected function getCodeColumn(): string
    {
        return 'reference_number';
    }

    protected function getCodePrefix(): string
    {
        return 'ACC-';
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
