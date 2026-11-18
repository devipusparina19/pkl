<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PasswordResetRequestController extends Controller
{
    public function index()
    {
        return view('super-admin.password-resets.index', $this->viewData());
    }

    /**
     * Menyetujui permintaan: buat password sementara, wajibkan ganti password,
     * dan putuskan semua sesi lama akun tersebut.
     *
     * Password sementara dikembalikan langsung sebagai respons (bukan flash
     * session) supaya tidak tersimpan di tabel sessions.
     */
    public function approve(Request $request, PasswordResetRequest $passwordReset)
    {
        $data = $request->validate([
            'verification_note' => ['required', 'string', 'min:5', 'max:255'],
        ], [
            'verification_note.required' => 'Catatan verifikasi identitas wajib diisi.',
            'verification_note.min' => 'Catatan verifikasi minimal :min karakter.',
        ]);

        $temp = $this->makeTemporaryPassword();
        $outcome = 'ok';
        $account = null;

        DB::transaction(function () use ($passwordReset, $data, $temp, &$outcome, &$account) {
            $row = PasswordResetRequest::query()
                ->whereKey($passwordReset->getKey())
                ->lockForUpdate()
                ->first();

            if (! $row || $row->status !== PasswordResetRequest::PENDING) {
                $outcome = 'handled';
                return;
            }

            $user = $row->user;

            if (! $user || ! in_array($user->role, ['admin', 'pimpinan'], true)) {
                $row->update([
                    'status' => PasswordResetRequest::CANCELLED,
                    'handled_by' => auth()->id(),
                    'handled_at' => now(),
                    'handler_note' => 'Akun tidak ditemukan atau tidak berhak direset.',
                ]);
                $outcome = 'invalid';
                return;
            }

            $user->forceFill([
                'password' => Hash::make($temp),
                'must_change_password' => true,
                'password_changed_at' => null,
                'remember_token' => Str::random(60),
            ])->save();

            if (config('session.driver') === 'database') {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }

            $row->update([
                'status' => PasswordResetRequest::APPROVED,
                'handled_by' => auth()->id(),
                'handled_at' => now(),
                'handler_note' => $data['verification_note'],
            ]);

            $account = $user;

            // Password TIDAK PERNAH dicatat ke log.
            Log::info('Password direset oleh Super Admin.', [
                'target_user_id' => $user->id,
                'handled_by' => auth()->id(),
                'request_id' => $row->id,
            ]);
        });

        if ($outcome === 'handled') {
            return redirect()
                ->route('super-admin.password-resets.index')
                ->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        if ($outcome === 'invalid' || ! $account) {
            return redirect()
                ->route('super-admin.password-resets.index')
                ->with('error', 'Akun tidak ditemukan atau tidak berhak direset. Permintaan dibatalkan.');
        }

        session()->now('success', 'Permintaan disetujui. Sampaikan password sementara di bawah ini langsung kepada pemilik akun.');

        return response()
            ->view('super-admin.password-resets.index', $this->viewData() + [
                'tempPassword' => [
                    'name' => $account->name,
                    'username' => $account->username ?: $account->email,
                    'password' => $temp,
                ],
            ])
            ->header('Cache-Control', 'no-store, private');
    }

    public function reject(Request $request, PasswordResetRequest $passwordReset)
    {
        $data = $request->validate([
            'handler_note' => ['required', 'string', 'min:5', 'max:255'],
        ], [
            'handler_note.required' => 'Alasan penolakan wajib diisi.',
            'handler_note.min' => 'Alasan penolakan minimal :min karakter.',
        ]);

        if ($passwordReset->status !== PasswordResetRequest::PENDING) {
            return redirect()
                ->route('super-admin.password-resets.index')
                ->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $passwordReset->update([
            'status' => PasswordResetRequest::REJECTED,
            'handled_by' => auth()->id(),
            'handled_at' => now(),
            'handler_note' => $data['handler_note'],
        ]);

        Log::info('Permintaan reset password ditolak.', [
            'request_id' => $passwordReset->id,
            'handled_by' => auth()->id(),
        ]);

        return redirect()
            ->route('super-admin.password-resets.index')
            ->with('success', 'Permintaan reset password ditolak.');
    }

    private function viewData(): array
    {
        return [
            'pending' => PasswordResetRequest::query()
                ->with('user:id,name,username,email,role')
                ->where('status', PasswordResetRequest::PENDING)
                ->latest()
                ->get(),

            'history' => PasswordResetRequest::query()
                ->with(['user:id,name', 'handler:id,name'])
                ->where('status', '!=', PasswordResetRequest::PENDING)
                ->latest('handled_at')
                ->limit(20)
                ->get(),
        ];
    }

    /** 12 karakter acak, minimal 1 huruf dan 1 angka, tanpa karakter yang membingungkan. */
    private function makeTemporaryPassword(int $length = 12): string
    {
        $letters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
        $digits = '23456789';
        $all = $letters . $digits;

        $chars = [
            $letters[random_int(0, strlen($letters) - 1)],
            $digits[random_int(0, strlen($digits) - 1)],
        ];

        while (count($chars) < $length) {
            $chars[] = $all[random_int(0, strlen($all) - 1)];
        }

        for ($i = count($chars) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$chars[$i], $chars[$j]] = [$chars[$j], $chars[$i]];
        }

        return implode('', $chars);
    }
}