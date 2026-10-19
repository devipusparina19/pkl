<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    protected $table = 'provinces';

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

    /**
     * Kabupaten, kota, dan mitra di bawah provinsi ini.
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'province_id');
    }
}