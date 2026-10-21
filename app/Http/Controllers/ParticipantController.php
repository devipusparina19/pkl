<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
                        ->orWhere('unit', 'like', "%{$search}%")
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

    public function create()
    {
        return view('participants.form', [
            'participant' => new Participant(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateParticipant($request);

        $temporaryPassword = Str::random(12);

        $participant = DB::transaction(function () use (
            $data,
            $temporaryPassword
        ) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($temporaryPassword),
                'role' => 'user',
            ]);

            return Participant::create([
                ...$data,
                'user_id' => $user->id,
            ]);
        });

        return redirect()
            ->route('participants.show', $participant)
            ->with('success', 'Peserta dan akun pengguna berhasil dibuat.')
            ->with('new_account_email', $data['email'])
            ->with('new_account_password', $temporaryPassword);
    }

    public function edit(Participant $participant)
    {
        return view('participants.form', compact('participant'));
    }

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

    public function destroy(Participant $participant)
    {
        DB::transaction(function () use ($participant) {
            $userId = $participant->user_id;

            // Hapus riwayat keikutsertaan milik peserta ini.
            $participant->histories()->delete();

            // Hapus data peserta.
            $participant->delete();

            // Hapus akun yang terhubung jika ada.
            if ($userId) {
                User::whereKey($userId)->delete();
            }
        });

        return redirect()
            ->route('participants.index')
            ->with('success', 'Data peserta berhasil dihapus.');
    }

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

            'unit' => [
                'nullable',
                'string',
                'max:255',
            ],

            'district' => [
                'nullable',
                'string',
                'max:255',
            ],

            'province' => [
                'nullable',
                'string',
                'max:255',
            ],

            'position' => [
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

            'notes' => [
                'nullable',
                'string',
            ],
        ]);
    }
}