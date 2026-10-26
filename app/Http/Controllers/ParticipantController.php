<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\User;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
                    foreach ([
                        'name',
                        'nip',
                        'institution',
                        'district',
                        'profession',
                        'email',
                    ] as $column) {
                        if (Schema::hasColumn('participants', $column)) {
                            $subQuery->orWhere($column, 'like', "%{$search}%");
                        }
                    }

                    if (Schema::hasColumn('participants', 'nik')) {
                        $subQuery->orWhere('nik', 'like', "%{$search}%");
                    }
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('participants.index', compact('participants', 'search'));
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

    public function edit(Participant $participant)
    {
        $districts = District::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name');

        if (
            $participant->district &&
            !$districts->contains($participant->district)
        ) {
            $districts->push($participant->district);
            $districts = $districts->sort()->values();
        }

        return view('participants.form', compact('participant', 'districts'));
    }

    public function update(Request $request, Participant $participant)
    {
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

    private function validateParticipant(
        Request $request,
        ?Participant $participant = null
    ): array {
        $rules = [
            'nip' => [
                'required',
                'string',
                'max:30',
                Rule::unique('participants', 'nip')->ignore($participant?->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', Rule::in(['L', 'P'])],
            'rank' => ['nullable', 'string', 'max:100'],
            'institution' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('participants', 'email')->ignore($participant?->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
        ];

        if (Schema::hasColumn('participants', 'nik')) {
            $rules['nik'] = [
                'nullable',
                'string',
                'max:30',
                Rule::unique('participants', 'nik')->ignore($participant?->id),
            ];
        }

        if (Schema::hasColumn('participants', 'gender')) {
            $rules['gender'] = ['nullable', Rule::in(['L', 'P'])];
        } else {
            unset($rules['gender']);
        }

        if (Schema::hasColumn('participants', 'rank')) {
            $rules['rank'] = ['nullable', 'string', 'max:100'];
        } else {
            unset($rules['rank']);
        }

        if (Schema::hasColumn('participants', 'email')) {
            $rules['email'] = [
                'nullable',
                'email',
                'max:255',
                Rule::unique('participants', 'email')->ignore($participant?->id),
            ];
        } else {
            unset($rules['email']);
        }

        $data = $request->validate($rules);

        // Hanya kirim field yang memang ada di tabel.
        return array_filter(
            $data,
            fn ($value, $key) => Schema::hasColumn('participants', $key),
            ARRAY_FILTER_USE_BOTH
        );
    }
}