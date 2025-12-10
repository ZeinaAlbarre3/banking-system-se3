<?php

namespace App\Domains\Account\Models;

use App\Domains\Account\Enums\AccountStateEnum;
use App\Domains\Account\Enums\AccountTypeEnum;
use App\Domains\Account\ValueObjects\Money;
use App\Domains\Auth\Models\User;
use App\Traits\HasUniqueCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasUniqueCode;

    protected $table = 'accounts';
    protected $fillable = [
        'user_id',
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

    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function isActive(): bool
    {
        return $this->state === AccountStateEnum::ACTIVE->value;
    }

    public function canBeClosed(): bool
    {
        return (float) $this->balance === 0.0;
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
        return 'AC-';
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
