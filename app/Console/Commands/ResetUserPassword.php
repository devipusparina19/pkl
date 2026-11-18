<?php

namespace App\Console\Commands;

use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ResetUserPassword extends Command
{
    protected $signature = 'arta:reset-password
        {identifier : Username atau email akun}
        {--force-change : Wajibkan pengguna mengganti password saat login berikutnya}';

    protected $description = 'Reset password akun lewat server (untuk Super Admin yang lupa password).';

    public function handle(): int
    {
        $identifier = trim((string) $this->argument('identifier'));

        $user = User::query()
            ->where('email', $identifier)
            ->orWhere('username', $identifier)
            ->first();

        if (! $user) {
            $this->error('Akun tidak ditemukan.');
            return self::FAILURE;
        }

        $this->info("Akun: {$user->name} ({$user->role})");

        $password = (string) $this->secret('Password baru');
        $confirm = (string) $this->secret('Ulangi password baru');

        if ($password !== $confirm) {
            $this->error('Konfirmasi password tidak sama.');
            return self::FAILURE;
        }

        $validator = Validator::make(
            ['password' => $password],
            ['password' => ['required', Password::min(8)->letters()->numbers()]]
        );

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return self::FAILURE;
        }

        DB::transaction(function () use ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'must_change_password' => (bool) $this->option('force-change'),
                'password_changed_at' => now(),
                'remember_token' => Str::random(60),
            ])->save();

            if (config('session.driver') === 'database') {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }

            PasswordResetRequest::create([
                'user_id' => $user->id,
                'username_snapshot' => (string) ($user->username ?: $user->email),
                'full_name_input' => (string) $user->name,
                'name_matches' => true,
                'status' => PasswordResetRequest::APPROVED,
                'handled_at' => now(),
                'handler_note' => 'Reset lewat perintah server (artisan).',
            ]);
        });

        Log::warning('Password direset lewat artisan.', [
            'target_user_id' => $user->id,
            'os_user' => get_current_user(),
        ]);

        $this->info('Password berhasil diubah. Semua sesi lama akun ini sudah diputus.');

        return self::SUCCESS;
    }
}