<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Halaman Profil / Pengaturan Akun milik pengguna yang sedang login.
 *
 * Keamanan:
 * - Selalu memakai $request->user() (dari session), TIDAK PERNAH ID dari input.
 * - Hanya kolom name, username, email, avatar, password yang disentuh.
 *   Kolom role / hak akses tidak pernah diubah di sini.
 */
class AccountController extends Controller
{
    public function edit(Request $request): View
    {
        return view('account.edit', [
            'user' => $request->user(),
        ]);
    }

    /* =====================================================
       Nama lengkap, username, email
    ===================================================== */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->merge([
            'name'     => trim((string) $request->input('name')),
            'username' => trim((string) $request->input('username')),
            'email'    => mb_strtolower(trim((string) $request->input('email'))),
        ]);

        $data = $request->validateWithBag('profile', [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'username' => [
                'required', 'string', 'min:3', 'max:30',
                'regex:/^[A-Za-z0-9._-]+$/',
                Rule::unique('users', 'username')->ignore($user->getKey(), $user->getKeyName()),
            ],
            'email' => [
                'required', 'string', 'email:rfc', 'max:255',
                Rule::unique('users', 'email')->ignore($user->getKey(), $user->getKeyName()),
            ],
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'name.min'          => 'Nama lengkap minimal :min karakter.',
            'name.max'          => 'Nama lengkap maksimal :max karakter.',
            'username.required' => 'Username wajib diisi.',
            'username.min'      => 'Username minimal :min karakter.',
            'username.max'      => 'Username maksimal :max karakter.',
            'username.regex'    => 'Username hanya boleh huruf, angka, titik (.), garis bawah (_) dan strip (-), tanpa spasi.',
            'username.unique'   => 'Username sudah digunakan pengguna lain.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.max'         => 'Email maksimal :max karakter.',
            'email.unique'      => 'Email sudah digunakan akun lain.',
        ]);

        $emailChanged = $data['email'] !== mb_strtolower((string) $user->email);

        DB::transaction(function () use ($user, $data, $emailChanged) {
            $user->name     = $data['name'];
            $user->username = $data['username'];
            $user->email    = $data['email'];

            /* Jika aplikasi memakai verifikasi email, email baru wajib diverifikasi ulang */
            if ($emailChanged && $user instanceof MustVerifyEmail) {
                $user->email_verified_at = null;
            }

            $user->save();
        });

        if ($emailChanged && $user instanceof MustVerifyEmail) {
            $user->sendEmailVerificationNotification();
        }

        return redirect()
            ->route('account.edit', ['tab' => 'info'])
            ->with('success', 'Informasi profil berhasil diperbarui.');
    }

    /* =====================================================
       Foto profil
    ===================================================== */

    /**
     * Menampilkan foto profil lewat Laravel (tanpa symlink public/storage).
     * Hanya untuk pengguna yang login; URL-nya relatif sehingga tidak
     * bergantung pada APP_URL maupun symlink storage:link.
     */
    public function showPhoto(User $user)
    {
        $path = $user->avatar;

        abort_unless($path && Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response($path, null, [
            'Cache-Control' => 'private, max-age=86400',
        ]);
    }
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validateWithBag('photo', [
            'photo' => [
                'required', 'file', 'image',
                'mimes:jpg,jpeg,png,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:2048',
                'dimensions:max_width=5000,max_height=5000',
            ],
        ], [
            'photo.required'   => 'Pilih foto terlebih dahulu.',
            'photo.image'      => 'File harus berupa gambar.',
            'photo.mimes'      => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'photo.mimetypes'  => 'Isi file bukan gambar JPG, PNG, atau WEBP yang valid.',
            'photo.max'        => 'Ukuran foto maksimal 2 MB.',
            'photo.dimensions' => 'Dimensi foto terlalu besar (maksimal 5000 x 5000 piksel).',
            'photo.uploaded'   => 'Foto gagal diunggah. Periksa ukuran file (maks. 2 MB) lalu coba lagi.',
        ]);

        $user    = $request->user();
        $oldPath = $user->avatar;

        /* store() membuat nama acak & ekstensi dari isi file, bukan dari nama asli */
        $newPath = $request->file('photo')->store('avatars', 'public');

        try {
            $user->avatar = $newPath;
            $user->save();
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($newPath);
            report($e);

            return redirect()
                ->route('account.edit', ['tab' => 'info'])
                ->with('error', 'Foto gagal disimpan. Silakan coba lagi.');
        }

        if ($oldPath && $oldPath !== $newPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()
            ->route('account.edit', ['tab' => 'info'])
            ->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function destroyPhoto(Request $request): RedirectResponse
    {
        $user = $request->user();
        $old  = $user->avatar;

        if ($old) {
            $user->avatar = null;
            $user->save();
            Storage::disk('public')->delete($old);
        }

        return redirect()
            ->route('account.edit', ['tab' => 'info'])
            ->with('success', 'Foto profil berhasil dihapus.');
    }

    /* =====================================================
       Password
    ===================================================== */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validateWithBag('password', [
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required', 'confirmed', 'different:current_password',
                Password::min(8)->letters()->numbers(),
            ],
        ], [
            'current_password.required'         => 'Password lama wajib diisi.',
            'current_password.current_password' => 'Password lama tidak sesuai.',
            'password.required'                 => 'Password baru wajib diisi.',
            'password.confirmed'                => 'Konfirmasi password baru tidak sama.',
            'password.different'                => 'Password baru harus berbeda dari password lama.',
            'password.min'                      => 'Password baru minimal :min karakter.',
            'password.letters'                  => 'Password baru harus mengandung huruf.',
            'password.numbers'                  => 'Password baru harus mengandung angka.',
        ]);

        $user = $request->user();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        /* Jaga agar sesi saat ini tetap valid bila middleware AuthenticateSession dipakai */
        $request->session()->put('password_hash_' . Auth::getDefaultDriver(), $user->getAuthPassword());

        return redirect()
            ->route('account.edit', ['tab' => 'security'])
            ->with('success', 'Password berhasil diubah.');
    }
}