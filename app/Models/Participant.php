<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Participant extends Model
{
    protected $fillable = [
        'user_id',
        'nik',
        'nip',
        'name',
        'gender',
        'rank',
        'institution',
        'unit',
        'district',
        'province',
        'position',
        'profession',
        'email',
        'phone',
        'notes',
    ];

    /**
     * Akun pengguna yang terhubung dengan peserta.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Semua riwayat pelatihan milik peserta.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(TrainingHistory::class);
    }
}