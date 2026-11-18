<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/*
 | Catatan: kolom `avatar` dan `must_change_password` sengaja TIDAK dimasukkan
 | ke Fillable. Keduanya hanya diisi langsung lewat forceFill() oleh
 | AccountController / alur reset password, sehingga tidak bisa dimanipulasi
 | lewat mass assignment dari form lain.
 */
#[Fillable([
    'name',
    'username',
    'email',
    'password',
    'role',
    'is_active',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
            'password_changed_at' => 'datetime',
        ];
    }

    /**
     * URL foto profil ($user->avatar_url), atau null jika belum ada foto.
     * Parameter ?v= mencegah browser menampilkan foto lama dari cache.
     */
    protected function avatarUrl(): Attribute
    {
        return Attribute::get(function () {
            $path = $this->attributes['avatar'] ?? null;

            if (empty($path)) {
                return null;
            }

            $version = $this->updated_at?->timestamp ?? 1;

            /* Foto dilayani lewat route Laravel (URL relatif), jadi tidak
             * bergantung pada symlink storage:link maupun APP_URL. */
            return route('account.photo.show', ['user' => $this->getKey()], false)
                . '?v=' . $version;
        });
    }
}