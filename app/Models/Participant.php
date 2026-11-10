<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Participant extends Model
{
    /**
     * Kolom yang dapat diisi melalui mass assignment.
     */
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
     * Relasi peserta dengan akun pengguna.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi peserta dengan riwayat pelatihan.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(
            TrainingHistory::class,
            'participant_id'
        );
    }
}