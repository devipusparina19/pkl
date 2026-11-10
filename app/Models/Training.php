<?php

namespace App\Models;

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
        'duration_days',
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
            'jpl' => 'integer',
        ];
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