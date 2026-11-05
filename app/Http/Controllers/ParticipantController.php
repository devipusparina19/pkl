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

        // Mengambil daftar tahun berdasarkan tanggal mulai pelatihan
        // yang memiliki riwayat peserta.
        $years = DB::table('training_histories')
            ->join(
                'trainings',
                'trainings.id',
                '=',
                'training_histories.training_id'
            )
            ->whereNotNull('trainings.start_date')
            ->selectRaw('YEAR(trainings.start_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($year) => (string) $year);

        // Menentukan filter tahun.
        $yearFilter = (string) $request->query('year', 'all');

        // Jika tahun tidak tersedia, tampilkan semua tahun.
        if (
            $yearFilter !== 'all' &&
            !$years->contains($yearFilter)
        ) {
            $yearFilter = 'all';
        }

        $selectedYear = $yearFilter !== 'all'
            ? $yearFilter
            : null;

        // Query utama data peserta.
        $participants = Participant::query()
            ->with(['histories.training'])
            ->withCount('histories')

            // Filter berdasarkan tahun pelatihan.
            ->when($selectedYear, function ($query) use ($selectedYear) {
                $query->whereHas('histories.training', function ($trainingQuery) use ($selectedYear) {
                    $trainingQuery->whereYear(
                        'start_date',
                        $selectedYear
                    );
                });
            })

            // Pencarian peserta.
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    foreach ([
                        'name',
                        'nip',
                        'institution',
                        'district',
                        'profession',
                        'email',
                        'phone',
                    ] as $column) {
                        if (Schema::hasColumn('participants', $column)) {
                            $subQuery->orWhere(
                                $column,
                                'like',
                                "%{$search}%"
                            );
                        }
                    }

                    if (Schema::hasColumn('participants', 'nik')) {
                        $subQuery->orWhere(
                            'nik',
                            'like',
                            "%{$search}%"
                        );
                    }
                });
            })

            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('participants.index', compact(
            'participants',
            'search',
            'years',
            'selectedYear',
            'yearFilter'
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

        return view('participants.form', compact(
            'participant',
            'districts'
        ));
    }

    public function update(Request $request, Participant $participant)
    {
        $data = $this->validateParticipant($request, $participant);

        DB::transaction(function () use ($data, $participant) {
            $participant->update($data);

            if ($participant->user_id) {
                User::whereKey($participant->user_id)->update([
                    'name' => $data['name'],
                    'email' => $data['email'] ?? null,
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

        if (!Schema::hasColumn('participants', 'gender')) {
            unset($rules['gender']);
        }

        if (!Schema::hasColumn('participants', 'rank')) {
            unset($rules['rank']);
        }

        if (!Schema::hasColumn('participants', 'email')) {
            unset($rules['email']);
        }

        $data = $request->validate($rules);

        return array_filter(
            $data,
            fn ($value, $key) => Schema::hasColumn('participants', $key),
            ARRAY_FILTER_USE_BOTH
        );
    }
}