<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingHistory extends Model
{
    protected $fillable = [
        'participant_id',
        'training_id',
        'source_file',
        'certificate_number',
        'status',
        'notes',
    ];

    /**
     * Peserta pemilik riwayat pelatihan.
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Data pelatihan yang diikuti peserta.
     */
    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }
}