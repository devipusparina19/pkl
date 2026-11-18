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

class AccountController extends Controller
{
    /**
     * Halaman Profil / Pengaturan Akun.
     */
    public function edit(Request $request): View
    {
        return view('account.edit', [
            'user' => $request->user(),
        ]);
    }

    /* =====================================================
       NAMA LENGKAP, USERNAME, EMAIL
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
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[A-Za-z0-9._-]+$/',
                Rule::unique('users', 'username')
                    ->ignore($user->getKey(), $user->getKeyName()),
            ],

            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->getKey(), $user->getKeyName()),
            ],
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'name.min'           => 'Nama lengkap minimal :min karakter.',
            'name.max'           => 'Nama lengkap maksimal :max karakter.',

            'username.required' => 'Username wajib diisi.',
            'username.min'      => 'Username minimal :min karakter.',
            'username.max'      => 'Username maksimal :max karakter.',
            'username.regex'    => 'Username hanya boleh huruf, angka, titik (.), garis bawah (_) dan strip (-), tanpa spasi.',
            'username.unique'   => 'Username sudah digunakan pengguna lain.',

            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.max'      => 'Email maksimal :max karakter.',
            'email.unique'   => 'Email sudah digunakan akun lain.',
        ]);

        $emailChanged = $data['email'] !== mb_strtolower((string) $user->email);

        DB::transaction(function () use ($user, $data, $emailChanged) {

            $user->name     = $data['name'];
            $user->username = $data['username'];
            $user->email    = $data['email'];

            // Jika menggunakan verifikasi email, verifikasi ulang email baru.
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
       FOTO PROFIL
    ===================================================== */

    /**
     * Menampilkan foto profil.
     */
    public function showPhoto(User $user)
    {
        $path = $user->avatar;

        abort_unless(
            $path && Storage::disk('public')->exists($path),
            404
        );

        return Storage::disk('public')->response($path, null, [
            'Cache-Control' => 'private, max-age=86400',
        ]);
    }

    /**
     * Memperbarui foto profil.
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validateWithBag('photo', [
            'photo' => [
                'required',
                'file',
                'image',
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

    /**
     * Menghapus foto profil.
     */
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
       PASSWORD
    ===================================================== */

    /**
     * Memperbarui password pengguna yang sedang login.
     *
     * Keamanan:
     * - Wajib memasukkan password lama.
     * - Password baru harus memenuhi validasi.
     * - Password disimpan menggunakan Hash::make().
     * - Penanda password sementara dihapus.
     * - Waktu perubahan password dicatat.
     * - Sesi perangkat lain dihapus jika menggunakan database session.
     * - Sesi pengguna saat ini tetap dipertahankan.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validateWithBag('password', [
            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'confirmed',
                'different:current_password',
                Password::min(8)->letters()->numbers(),
            ],
        ], [
            'current_password.required' =>
                'Password lama wajib diisi.',

            'current_password.current_password' =>
                'Password lama tidak sesuai.',

            'password.required' =>
                'Password baru wajib diisi.',

            'password.confirmed' =>
                'Konfirmasi password baru tidak sama.',

            'password.different' =>
                'Password baru harus berbeda dari password lama.',

            'password.min' =>
                'Password baru minimal :min karakter.',

            'password.letters' =>
                'Password baru harus mengandung huruf.',

            'password.numbers' =>
                'Password baru harus mengandung angka.',
        ]);

        $user = $request->user();

        // Simpan password baru dan catat perubahan secara transaksional.
        DB::transaction(function () use ($user, $request) {

            $user->forceFill([
                'password' => Hash::make(
                    $request->input('password')
                ),

                'must_change_password' => false,

                'password_changed_at' => now(),
            ])->save();

            // Hapus sesi pengguna lain jika menggunakan database session.
            if (config('session.driver') === 'database') {

                $sessionTable = config('session.table', 'sessions');

                DB::table($sessionTable)
                    ->where('user_id', $user->getKey())
                    ->where(
                        'id',
                        '!=',
                        $request->session()->getId()
                    )
                    ->delete();
            }
        });

        // Perbarui hash password pada session saat ini.
        // Diperlukan jika middleware AuthenticateSession digunakan.
        $request->session()->put(
            'password_hash_' . Auth::getDefaultDriver(),
            $user->getAuthPassword()
        );

        // Regenerasi session untuk menjaga keamanan.
        $request->session()->regenerate();

        return redirect()
            ->route('account.edit', ['tab' => 'security'])
            ->with(
                'success',
                'Password berhasil diubah. Keamanan akun Anda telah diperbarui.'
            );
    }
}