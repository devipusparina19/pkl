<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    protected $fillable = [
        'code',
        'name',
        'training_category_id',

        // Kolom lama dipertahankan untuk kompatibilitas.
        'field',
        'location',

        'batch_number',
        'funding_source',
        'organizer',
        'start_date',
        'end_date',

        // Durasi hasil import Excel / hitungan otomatis (tidak diedit admin).
        'duration_days',

        // Penyesuaian durasi manual oleh admin.
        'duration_manual',
        'duration_note',
        'duration_adjusted_at',
        'duration_adjusted_by',

        'district',
        'jpl',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'training_category_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'duration_days' => 'integer',
            'duration_manual' => 'integer',
            'duration_adjusted_at' => 'datetime',
            'duration_adjusted_by' => 'integer',
            'jpl' => 'integer',
        ];
    }

    /**
     * Durasi yang dipakai di seluruh sistem:
     * nilai manual jika ada, selain itu nilai dari Excel.
     */
    public function getEffectiveDurationAttribute(): ?int
    {
        return $this->duration_manual ?? $this->duration_days;
    }

    /**
     * True jika admin sudah mengubah durasi secara manual.
     */
    public function getIsDurationManualAttribute(): bool
    {
        return $this->duration_manual !== null;
    }

    /**
     * Relasi ke kategori pelatihan.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(
            TrainingCategory::class,
            'training_category_id'
        );
    }

    /**
     * Relasi ke admin yang terakhir menyesuaikan durasi.
     */
    public function durationAdjuster(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'duration_adjusted_by'
        );
    }

    /**
     * Relasi ke riwayat peserta yang mengikuti pelatihan.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(
            TrainingHistory::class,
            'training_id'
        );
    }
}