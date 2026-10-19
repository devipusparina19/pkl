<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingYear extends Model
{
    protected $table = 'training_years';

    protected $fillable = [
        'year',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}