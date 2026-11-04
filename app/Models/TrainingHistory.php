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

    public function participant(): BelongsTo
    {
        return $this->belongsTo(
            Participant::class,
            'participant_id'
        );
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(
            Training::class,
            'training_id'
        );
    }
}