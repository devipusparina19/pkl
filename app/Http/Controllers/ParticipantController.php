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
    /**
     * Menampilkan daftar peserta.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        /*
         * Mengambil tahun pelatihan yang memiliki riwayat peserta.
         * Mendukung MySQL dan SQLite.
         */
        $yearsQuery = DB::table('training_histories')
            ->join(
                'trainings',
                'trainings.id',
                '=',
                'training_histories.training_id'
            )
            ->whereNotNull('trainings.start_date');

        if (DB::connection()->getDriverName() === 'sqlite') {
            $yearsQuery->selectRaw(
                "CAST(strftime('%Y', trainings.start_date) AS INTEGER) as year"
            );
        } else {
            $yearsQuery->selectRaw(
                'YEAR(trainings.start_date) as year'
            );
        }

        $years = $yearsQuery
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($year) => (string) $year)
            ->values();

        /*
         * Filter tahun.
         */
        $yearFilter = (string) $request->query('year', 'all');

        if (
            $yearFilter !== 'all' &&
            !$years->contains($yearFilter)
        ) {
            $yearFilter = 'all';
        }

        $selectedYear = $yearFilter !== 'all'
            ? $yearFilter
            : null;

        /*
         * Query utama peserta.
         */
        $participants = Participant::query()
            ->with(['histories.training'])
            ->withCount('histories')

            ->when($selectedYear, function ($query) use ($selectedYear) {
                $query->whereHas(
                    'histories.training',
                    function ($trainingQuery) use ($selectedYear) {
                        $trainingQuery->whereYear(
                            'start_date',
                            $selectedYear
                        );
                    }
                );
            })

            /*
             * Pencarian peserta.
             */
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $columns = [
                        'name',
                        'nip',
                        'institution',
                        'district',
                        'profession',
                        'email',
                        'phone',
                        'nik',
                    ];

                    foreach ($columns as $column) {
                        if (
                            Schema::hasColumn(
                                'participants',
                                $column
                            )
                        ) {
                            $subQuery->orWhere(
                                $column,
                                'like',
                                "%{$search}%"
                            );
                        }
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

    /**
     * Menampilkan detail peserta.
     */
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
        /*
         * Ambil semua wilayah aktif dari master data.
         */
        $districts = District::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name');

        /*
         * Pertahankan wilayah lama jika sudah tidak aktif
         * atau belum terdaftar di master data.
         *
         * Tujuannya agar data lama tidak hilang saat diedit.
         */
        $currentDistrict = trim(
            (string) $participant->district
        );

        if (
            $currentDistrict !== '' &&
            !$districts->contains($currentDistrict)
        ) {
            $districts->push($currentDistrict);
            $districts = $districts
                ->unique()
                ->sort()
                ->values();
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
        $data = $this->validateParticipant(
            $request,
            $participant
        );

        DB::transaction(function () use ($data, $participant) {
            /*
             * Perbarui data peserta.
             * Kolom district ikut diperbarui di sini.
             */
            $participant->update($data);

            /*
             * Sinkronkan nama dan email akun pengguna
             * jika peserta memiliki akun.
             */
            if ($participant->user_id) {
                $userData = [];

                if (array_key_exists('name', $data)) {
                    $userData['name'] = $data['name'];
                }

                if (array_key_exists('email', $data)) {
                    $userData['email'] = $data['email'];
                }

                if (!empty($userData)) {
                    User::whereKey($participant->user_id)
                        ->update($userData);
                }
            }
        });

        return redirect()
            ->route('participants.show', $participant)
            ->with(
                'success',
                'Data peserta berhasil diperbarui.'
            );
    }

    /**
     * Menghapus peserta.
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
            ->with(
                'success',
                'Data peserta berhasil dihapus.'
            );
    }

    /**
     * Validasi data peserta.
     */
    private function validateParticipant(
        Request $request,
        ?Participant $participant = null
    ): array {
        $rules = [
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

            /*
             * Wilayah harus berasal dari master data District.
             */
            'district' => [
                'required',
                'string',
                'max:255',
                Rule::exists('districts', 'name')
                    ->where('is_active', true),
            ],

            'profession' => [
                'nullable',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('participants', 'email')
                    ->ignore($participant?->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],
        ];

        /*
         * Validasi NIK jika kolom tersedia.
         */
        if (Schema::hasColumn('participants', 'nik')) {
            $rules['nik'] = [
                'nullable',
                'string',
                'max:30',
                Rule::unique('participants', 'nik')
                    ->ignore($participant?->id),
            ];
        }

        /*
         * Hilangkan aturan untuk kolom yang tidak tersedia.
         */
        $optionalColumns = [
            'gender',
            'rank',
            'email',
            'phone',
            'institution',
            'district',
            'profession',
        ];

        foreach ($optionalColumns as $column) {
            if (
                !Schema::hasColumn('participants', $column)
            ) {
                unset($rules[$column]);
            }
        }

        $data = $request->validate($rules);

        /*
         * Hanya simpan kolom yang benar-benar tersedia
         * pada tabel participants.
         */
        return array_filter(
            $data,
            fn ($value, $key) => Schema::hasColumn(
                'participants',
                $key
            ),
            ARRAY_FILTER_USE_BOTH
        );
    }
}