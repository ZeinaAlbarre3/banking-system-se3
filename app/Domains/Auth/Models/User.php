<?php

namespace App\Domains\Auth\Models;

use App\Domains\Account\Models\Account;
use App\Domains\Transaction\Models\AuditLog;
use App\Domains\Transaction\Models\ScheduledTransaction;
use App\Traits\HasUniqueCode;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable ,HasApiTokens , HasRoles, HasUniqueCode;

    protected $guard_name = 'web';
    /**

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'reference_number',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'actor_id');
    }

    public function scheduleTransactions(): HasMany
    {
        return $this->hasMany(ScheduledTransaction::class);
    }


    public function auditableLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    protected function getCodePrefix(): string
    {
        return 'US-';
    }

    protected function getCodePadding(): int
    {
        return 6;
    }

    public function getRouteKeyName(): string
    {
        return 'reference_number';
    }

    protected function getCodeColumn(): string
    {
        return 'reference_number';
    }


}
