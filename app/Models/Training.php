<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    protected $fillable = [
        'code',
        'name',
        'field',
        'batch_number',
        'funding_source',
        'organizer',
        'start_date',
        'end_date',
        'duration_days',
        'district',
        'location',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function histories(): HasMany
    {
        return $this->hasMany(
            TrainingHistory::class,
            'training_id'
        );
    }
}