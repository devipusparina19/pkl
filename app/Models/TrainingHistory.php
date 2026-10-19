<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingHistory extends Model
{
    /**
     * Kolom riwayat pelatihan yang boleh diisi.
     */
    protected $fillable = [
        'participant_id',
        'training_id',
        'source_file',
        'certificate_number',
        'status',
        'notes',
    ];

    /**
     * Peserta yang mengikuti pelatihan.
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Data pelatihan yang diikuti.
     */
    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }
}
