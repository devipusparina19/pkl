<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicTrainingHistoryController extends Controller
{
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

        // Simpan ID peserta untuk dibuka pada halaman hasil.
        session([
            'public_training_history_participant_id' => $participant->id,
        ]);

        return redirect()->route('public.training-history.result');
    }

    /**
     * Menampilkan halaman hasil pencarian.
     */
    public function result()
    {
        $participantId = session(
            'public_training_history_participant_id'
        );

        if (!$participantId) {
            return redirect()
                ->route('public.training-history')
                ->with(
                    'lookup_error',
                    'Silakan cari riwayat pelatihan terlebih dahulu.'
                );
        }

        $participant = Participant::query()
            ->with([
                'histories' => function ($query) {
                    $query->with('training')
                        ->orderByDesc('created_at');
                },
            ])
            ->find($participantId);

        if (!$participant) {
            session()->forget(
                'public_training_history_participant_id'
            );

            return redirect()
                ->route('public.training-history')
                ->with(
                    'lookup_error',
                    'Data peserta tidak ditemukan atau sudah tidak tersedia.'
                );
        }

        return view('public.training-history', [
            'participant' => $participant,
            'searched' => true,
        ]);
    }
}