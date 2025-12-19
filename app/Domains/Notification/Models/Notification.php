<?php

namespace App\Domains\Notification\Models;

use App\Domains\Account\Models\Account;
use App\Domains\Auth\Models\User;
use App\Traits\HasUniqueCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory, HasUniqueCode;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'data',
        'read',
    ];

    protected $casts = [
        'data' => 'array',
        'read' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
