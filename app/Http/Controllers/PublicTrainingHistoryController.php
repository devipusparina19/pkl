<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PublicTrainingHistoryController extends Controller
{
    private const SESSION_KEY = 'public_training_history_participant_id';

    /**
     * Header agar browser, proxy, atau CDN tidak menyimpan halaman lama.
     */
    private function noCache(Response $response): Response
    {
        return $response
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Ambil peserta + riwayat langsung dari database (selalu data terbaru).
     * Dipakai bersama oleh halaman hasil dan halaman ekspor.
     */
    private function freshParticipant(int $participantId): ?Participant
    {
        return Participant::query()
            ->with([
                'histories' => function ($query) {
                    $query->with('training')
                        ->orderByDesc('created_at');
                },
            ])
            ->find($participantId);
    }

    /**
     * Menampilkan halaman pencarian riwayat pelatihan.
     */
    public function index()
    {
        return view('public.training-history-search', [
            'inputNik' => '',
        ]);
    }

    /**
     * Memproses pencarian berdasarkan NIK saja.
     */
    public function lookup(Request $request)
    {
        $nik = trim((string) $request->input('nik'));

        $validator = Validator::make(
            [
                'nik' => $nik,
            ],
            [
                'nik' => ['required', 'string', 'max:30'],
            ],
            [
                'nik.required' => 'NIK wajib diisi.',
                'nik.string' => 'Format NIK tidak valid.',
                'nik.max' => 'NIK maksimal 30 karakter.',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->route('public.training-history')
                ->withErrors($validator)
                ->withInput();
        }

        $participant = Participant::query()
            ->where('nik', $nik)
            ->first();

        if (!$participant) {
            return redirect()
                ->route('public.training-history')
                ->withInput()
                ->with(
                    'lookup_error',
                    'Data peserta tidak ditemukan. Pastikan NIK yang dimasukkan sesuai dengan data yang terdaftar di SIRAPIK.'
                );
        }

        // Hanya ID yang disimpan di session; datanya selalu dibaca ulang dari database.
        session([
            self::SESSION_KEY => $participant->id,
        ]);

        return redirect()->route('public.training-history.result');
    }

    /**
     * Menampilkan halaman hasil pencarian.
     */
    public function result()
    {
        $participantId = session(self::SESSION_KEY);

        if (!$participantId) {
            return redirect()
                ->route('public.training-history')
                ->with(
                    'lookup_error',
                    'Silakan cari riwayat pelatihan terlebih dahulu.'
                );
        }

        $participant = $this->freshParticipant((int) $participantId);

        if (!$participant) {
            session()->forget(self::SESSION_KEY);

            return redirect()
                ->route('public.training-history')
                ->with(
                    'lookup_error',
                    'Data peserta tidak ditemukan atau sudah tidak tersedia.'
                );
        }

        return $this->noCache(
            response()->view('public.training-history', [
                'participant' => $participant,
                'searched' => true,
            ])
        );
    }

    /**
     * Halaman ekspor / cetak PDF publik.
     * Data dibaca ulang dari database setiap kali halaman dibuka.
     */
    public function export()
    {
        $participantId = session(self::SESSION_KEY);

        if (!$participantId) {
            return redirect()
                ->route('public.training-history')
                ->with(
                    'lookup_error',
                    'Silakan cari riwayat pelatihan terlebih dahulu.'
                );
        }

        $participant = $this->freshParticipant((int) $participantId);

        if (!$participant) {
            session()->forget(self::SESSION_KEY);

            return redirect()
                ->route('public.training-history')
                ->with(
                    'lookup_error',
                    'Data peserta tidak ditemukan atau sudah tidak tersedia.'
                );
        }

        return $this->noCache(
            response()->view('public.training-history-export', [   // sesuaikan dengan nama file Blade ekspor Anda
                'participant' => $participant,
                'histories' => $participant->histories,
                'printedAt' => Carbon::now(),
            ])
        );
    }
}