<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\User;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ParticipantController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $participants = Participant::query()
            ->with(['histories.training'])
            ->withCount('histories')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nik', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('institution', 'like', "%{$search}%")
                        ->orWhere('district', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('participants.index', compact(
            'participants',
            'search'
        ));
    }

    public function show(Participant $participant)
    {
        $participant->load([
            'histories' => function ($query) {
                $query->with('training')
                    ->orderByDesc(
                        DB::raw(
                            '(SELECT start_date FROM trainings
                             WHERE trainings.id = training_histories.training_id)'
                        )
                    );
            },
        ]);

        return view('participants.show', compact('participant'));
    }

    /**
     * Menampilkan form edit peserta.
     */
    public function edit(Participant $participant)
    {
        $districts = District::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name');

        // Pertahankan pilihan lama jika kabupaten sudah tidak aktif.
        if (
            $participant->district &&
            !$districts->contains($participant->district)
        ) {
            $districts->push($participant->district);
            $districts = $districts->sort()->values();
        }

        return view('participants.form', compact(
            'participant',
            'districts'
        ));
    }

    /**
     * Memperbarui data peserta.
     */
    public function update(
        Request $request,
        Participant $participant
    ) {
        $data = $this->validateParticipant($request, $participant);

        DB::transaction(function () use ($data, $participant) {
            $participant->update($data);

            if ($participant->user_id) {
                User::whereKey($participant->user_id)->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                ]);
            }
        });

        return redirect()
            ->route('participants.show', $participant)
            ->with('success', 'Data peserta berhasil diperbarui.');
    }

    /**
     * Menghapus peserta beserta riwayat dan akun terkait.
     */
    public function destroy(Participant $participant)
    {
        DB::transaction(function () use ($participant) {
            $userId = $participant->user_id;

            $participant->histories()->delete();
            $participant->delete();

            if ($userId) {
                User::whereKey($userId)->delete();
            }
        });

        return redirect()
            ->route('participants.index')
            ->with('success', 'Data peserta berhasil dihapus.');
    }

    /**
     * Validasi untuk pembaruan data peserta.
     */
    private function validateParticipant(
        Request $request,
        ?Participant $participant = null
    ): array {
        return $request->validate([
            'nik' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('participants', 'nik')
                    ->ignore($participant?->id),
            ],

            'nip' => [
                'required',
                'string',
                'max:30',
                Rule::unique('participants', 'nip')
                    ->ignore($participant?->id),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'gender' => [
                'nullable',
                Rule::in(['L', 'P']),
            ],

            'rank' => [
                'nullable',
                'string',
                'max:100',
            ],

            'institution' => [
                'nullable',
                'string',
                'max:255',
            ],

            'district' => [
                'nullable',
                'string',
                'max:255',
            ],

            'profession' => [
                'nullable',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('participants', 'email')
                    ->ignore($participant?->id),
                Rule::unique('users', 'email')
                    ->ignore($participant?->user_id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],
        ]);
    }
}