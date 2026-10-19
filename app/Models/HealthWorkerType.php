<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthWorkerType extends Model
{
    protected $table = 'health_worker_types';

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}