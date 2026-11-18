<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordResetRequest extends Model
{
    public const PENDING = 'pending';
    public const APPROVED = 'approved';
    public const REJECTED = 'rejected';
    public const CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'username_snapshot',
        'full_name_input',
        'name_matches',
        'note',
        'status',
        'requested_ip',
        'requested_user_agent',
        'handled_by',
        'handled_at',
        'handler_note',
    ];

    protected function casts(): array
    {
        return [
            'name_matches' => 'boolean',
            'handled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}