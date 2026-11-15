<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingYear;
use App\Models\TrainingCategory;
use App\Models\FundingSource;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrainingController extends Controller
{
    /**
     * Menampilkan daftar pelatihan, pencarian, dan filter tahun.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $year = $request->input('year', '');

        // Mengambil tahun aktif dari Master Data.
        $years = TrainingYear::query()
            ->where('is_active', true)
            ->orderByDesc('year')
            ->pluck('year');

        // Mengambil data pelatihan.
        $trainings = Training::query()
            ->with('category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('organizer', 'like', "%{$search}%")
                        ->orWhere('batch_number', 'like', "%{$search}%")
                        ->orWhere('funding_source', 'like', "%{$search}%")
                        ->orWhere('district', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where(
                                'name',
                                'like',
                                "%{$search}%"
                            );
                        });
                });
            })
            ->when(
                $year !== '' && ctype_digit((string) $year),
                function ($query) use ($year) {
                    $query->whereYear('start_date', (int) $year);
                }
            )
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('trainings.index', compact(
            'trainings',
            'search',
            'year',
            'years'
        ));
    }

    /**
     * Menampilkan detail pelatihan dan daftar peserta.
     */
    public function show(Request $request, Training $training)
    {
        // Memuat kategori pelatihan.
        $training->load('category');

        $search = trim((string) $request->input('search', ''));

        // Mengambil daftar peserta berdasarkan training_histories.
        // Relasi:
        // training_histories.training_id -> trainings.id
        // training_histories.participant_id -> participants.id
        $participantQuery = DB::table('training_histories as th')
            ->join('participants as p', 'p.id', '=', 'th.participant_id')
            ->where('th.training_id', $training->id)
            ->select([
                'th.id as history_id',
                'th.certificate_number',
                'th.status as participation_status',
                'th.notes as history_notes',
                'p.id as participant_id',
                'p.nip',
                'p.nik',
                'p.name as participant_name',
                'p.gender',
                'p.institution',
                'p.unit',
                'p.district as participant_district',
                'p.province',
                'p.position',
                'p.profession',
                'p.email',
                'p.phone',
                'p.rank',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('p.name', 'like', "%{$search}%")
                        ->orWhere('p.nip', 'like', "%{$search}%")
                        ->orWhere('p.nik', 'like', "%{$search}%")
                        ->orWhere('p.institution', 'like', "%{$search}%")
                        ->orWhere('p.unit', 'like', "%{$search}%")
                        ->orWhere('p.profession', 'like', "%{$search}%")
                        ->orWhere('p.position', 'like', "%{$search}%")
                        ->orWhere('p.district', 'like', "%{$search}%")
                        ->orWhere('p.province', 'like', "%{$search}%")
                        ->orWhere('th.certificate_number', 'like', "%{$search}%");
                });
            });

        // Jumlah seluruh peserta pada pelatihan ini.
        $totalParticipants = DB::table('training_histories')
            ->where('training_id', $training->id)
            ->count();

        // Daftar peserta dengan pagination.
        $participants = $participantQuery
            ->orderBy('p.name')
            ->paginate(15)
            ->withQueryString();

        return view('trainings.show', compact(
            'training',
            'participants',
            'totalParticipants',
            'search'
        ));
    }

    /**
     * Menampilkan form tambah pelatihan.
     */
    public function create()
    {
        $categories = TrainingCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $fundingSources = FundingSource::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $districts = District::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('trainings.create', compact(
            'categories',
            'fundingSources',
            'districts'
        ));
    }

    /**
     * Menyimpan data pelatihan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'training_category_id' => [
                'required',
                'integer',
                'exists:training_categories,id',
            ],
            'batch_number' => [
                'nullable',
                'string',
                'max:100',
            ],
            'funding_source' => [
                'nullable',
                'string',
                'max:255',
            ],
            'organizer' => [
                'nullable',
                'string',
                'max:255',
            ],
            'start_date' => [
                'required',
                'date',
            ],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
            'jpl' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'district' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ], [
            'name.required' => 'Nama pelatihan wajib diisi.',
            'training_category_id.required' => 'Kategori pelatihan wajib dipilih.',
            'training_category_id.exists' => 'Kategori pelatihan tidak ditemukan.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'jpl.integer' => 'JPL harus berupa angka.',
            'jpl.min' => 'JPL tidak boleh kurang dari 0.',
        ]);

        // Menghitung durasi pelatihan secara inklusif.
        $validated['duration_days'] = $this->calculateDuration(
            $validated['start_date'],
            $validated['end_date'] ?? null
        );

        Training::create($validated);

        return redirect()
            ->route('trainings.index')
            ->with('success', 'Data pelatihan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit pelatihan.
     */
    public function edit(Training $training)
    {
        // Kategori aktif dan kategori yang sedang digunakan.
        $categories = TrainingCategory::query()
            ->where(function ($query) use ($training) {
                $query->where('is_active', true);

                if ($training->training_category_id) {
                    $query->orWhere(
                        'id',
                        $training->training_category_id
                    );
                }
            })
            ->orderBy('name')
            ->get();

        $fundingSources = FundingSource::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $districts = District::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('trainings.edit', compact(
            'training',
            'categories',
            'fundingSources',
            'districts'
        ));
    }

    /**
     * Memperbarui data pelatihan.
     */
    public function update(Request $request, Training $training)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'training_category_id' => [
                'required',
                'integer',
                'exists:training_categories,id',
            ],
            'batch_number' => [
                'nullable',
                'string',
                'max:100',
            ],
            'funding_source' => [
                'nullable',
                'string',
                'max:255',
            ],
            'organizer' => [
                'nullable',
                'string',
                'max:255',
            ],
            'start_date' => [
                'required',
                'date',
            ],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
            'jpl' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'district' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ], [
            'name.required' => 'Nama pelatihan wajib diisi.',
            'training_category_id.required' => 'Kategori pelatihan wajib dipilih.',
            'training_category_id.exists' => 'Kategori pelatihan tidak ditemukan.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'jpl.integer' => 'JPL harus berupa angka.',
            'jpl.min' => 'JPL tidak boleh kurang dari 0.',
        ]);

        // Menghitung ulang durasi pelatihan.
        $validated['duration_days'] = $this->calculateDuration(
            $validated['start_date'],
            $validated['end_date'] ?? null
        );

        $training->update($validated);

        return redirect()
            ->route('trainings.index', array_filter([
                'search' => $request->query('search'),
                'year' => $request->query('year'),
            ]))
            ->with('success', 'Data pelatihan berhasil diperbarui.');
    }

    /**
     * Menghitung durasi pelatihan dalam hari.
     */
    private function calculateDuration(
        string $startDate,
        ?string $endDate
    ): int {
        if (!$endDate) {
            return 0;
        }

        return Carbon::parse($startDate)
            ->diffInDays(Carbon::parse($endDate)) + 1;
    }

    /**
     * Menghapus pelatihan jika belum memiliki riwayat peserta.
     */
    public function destroy(Training $training)
    {
        if ($training->histories()->exists()) {
            return redirect()
                ->route('trainings.index')
                ->with(
                    'error',
                    'Pelatihan tidak dapat dihapus karena sudah memiliki riwayat peserta.'
                );
        }

        $training->delete();

        return redirect()
            ->route('trainings.index')
            ->with('success', 'Data pelatihan berhasil dihapus.');
    }

    /**
     * Mengunduh template Excel resmi SIRAPIK.
     */
    public function downloadTemplate()
    {
        $path = storage_path('app/templates/Template_sirapik.xlsx');

        if (!file_exists($path)) {
            abort(
                404,
                'File template Excel tidak ditemukan. Periksa folder storage/app/templates.'
            );
        }

        return response()->download(
            $path,
            'Template_SIRAPIK.xlsx',
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }
}