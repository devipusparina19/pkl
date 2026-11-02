<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\FundingSource;
use App\Models\HealthWorkerType;
use App\Models\Province;
use App\Models\TrainingYear;
use App\Models\TrainingCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class MasterDataController extends Controller
{
    private const CATEGORIES = [
        'provinces' => [
            'title' => 'Provinsi',
            'model' => Province::class,
            'singular' => 'provinsi',
        ],
        'districts' => [
            'title' => 'Kabupaten/Kota/Mitra/Mandiri',
            'model' => District::class,
            'singular' => 'wilayah',
        ],
        'funding-sources' => [
            'title' => 'Sumber Dana',
            'model' => FundingSource::class,
            'singular' => 'sumber dana',
        ],
        'training-years' => [
            'title' => 'Tahun Pelatihan',
            'model' => TrainingYear::class,
            'singular' => 'tahun pelatihan',
        ],
        'health-worker-types' => [
            'title' => 'Jenis Tenaga Kesehatan',
            'model' => HealthWorkerType::class,
            'singular' => 'jenis tenaga kesehatan',
        ],
        'training-categories' => [
            'title' => 'Kategori Pelatihan',
            'model' => TrainingCategory::class,
            'singular' => 'kategori pelatihan',
        ],
    ];

    public function index(Request $request, string $category): View
    {
        $config = $this->categoryConfig($category);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
        ]);

        $model = $config['model'];
        $query = $model::query();

        if ($category === 'districts') {
            $query->with('province');
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($builder) use ($search, $category) {
                if ($category === 'training-years') {
                    $builder->where('year', 'like', "%{$search}%");
                } else {
                    $builder->where('name', 'like', "%{$search}%");
                }

                if ($category === 'districts') {
                    $builder->orWhereHas('province', function ($provinceQuery) use ($search) {
                        $provinceQuery->where('name', 'like', "%{$search}%");
                    });
                }
            });
        }

        if (isset($filters['status'])) {
            $query->where(
                'is_active',
                $filters['status'] === 'active'
            );
        }

        if ($category === 'training-years') {
            $query->orderByDesc('year');
        } else {
            $query->orderBy('name');
        }

        $items = $query->paginate(10)->withQueryString();

        return view('super-admin.master-data.index', [
            'category' => $category,
            'config' => $config,
            'items' => $items,
            'filters' => $filters,
        ]);
    }

    public function create(string $category): View
    {
        $config = $this->categoryConfig($category);

        return view('super-admin.master-data.create', [
            'category' => $category,
            'config' => $config,
            'provinces' => $this->activeProvinces(),
        ]);
    }

    public function store(Request $request, string $category): RedirectResponse
    {
        $config = $this->categoryConfig($category);
        $validated = $this->validateData($request, $category);

        $model = $config['model'];
        $model::create($validated);

        return redirect()
            ->route('super-admin.master-data.index', ['category' => $category])
            ->with('success', $config['title'] . ' berhasil ditambahkan.');
    }

    public function edit(string $category, int $id): View
    {
        $config = $this->categoryConfig($category);
        $item = $this->findItem($category, $id);

        return view('super-admin.master-data.edit', [
            'category' => $category,
            'config' => $config,
            'item' => $item,
            'provinces' => $this->activeProvinces(),
        ]);
    }

    public function update(
        Request $request,
        string $category,
        int $id
    ): RedirectResponse {
        $config = $this->categoryConfig($category);
        $item = $this->findItem($category, $id);

        $validated = $this->validateData(
            $request,
            $category,
            $item->id
        );

        $item->update($validated);

        return redirect()
            ->route('super-admin.master-data.index', ['category' => $category])
            ->with('success', $config['title'] . ' berhasil diperbarui.');
    }

    public function toggleStatus(
        string $category,
        int $id
    ): RedirectResponse {
        $this->categoryConfig($category);
        $item = $this->findItem($category, $id);

        $item->is_active = !$item->is_active;
        $item->save();

        $message = $item->is_active
            ? 'Data berhasil diaktifkan.'
            : 'Data berhasil dinonaktifkan.';

        return redirect()
            ->route('super-admin.master-data.index', ['category' => $category])
            ->with('success', $message);
    }

    public function destroy(
        string $category,
        int $id
    ): RedirectResponse {
        $config = $this->categoryConfig($category);
        $item = $this->findItem($category, $id);

        if (
            $category === 'provinces'
            && $item->districts()->exists()
        ) {
            return back()->with(
                'error',
                'Provinsi tidak dapat dihapus karena masih memiliki data wilayah. Nonaktifkan provinsi jika sudah tidak digunakan.'
            );
        }

        if (
            $category === 'training-categories'
            && $item->trainings()->exists()
        ) {
            return back()->with(
                'error',
                'Kategori tidak dapat dihapus karena sudah digunakan oleh data pelatihan. Nonaktifkan kategori jika sudah tidak digunakan.'
            );
        }

        $item->delete();

        return redirect()
            ->route('super-admin.master-data.index', ['category' => $category])
            ->with('success', $config['title'] . ' berhasil dihapus.');
    }

    private function validateData(
        Request $request,
        string $category,
        ?int $ignoreId = null
    ): array {
        $rules = match ($category) {
            'provinces' => [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('provinces', 'name')->ignore($ignoreId),
                ],
                'is_active' => ['required', 'boolean'],
            ],

            'districts' => [
                'province_id' => [
                    'required',
                    'integer',
                    Rule::exists('provinces', 'id')->where('is_active', true),
                ],
                'name' => ['required', 'string', 'max:255'],
                'type' => [
                    'required',
                    Rule::in(['kabupaten', 'kota', 'mitra', 'mandiri']),
                ],
                'is_active' => ['required', 'boolean'],
            ],

            'funding-sources' => [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('funding_sources', 'name')->ignore($ignoreId),
                ],
                'is_active' => ['required', 'boolean'],
            ],

            'training-years' => [
                'year' => [
                    'required',
                    'integer',
                    'min:1900',
                    'max:2155',
                    Rule::unique('training_years', 'year')->ignore($ignoreId),
                ],
                'is_active' => ['required', 'boolean'],
            ],

            'health-worker-types' => [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('health_worker_types', 'name')->ignore($ignoreId),
                ],
                'is_active' => ['required', 'boolean'],
            ],

            'training-categories' => [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('training_categories', 'name')->ignore($ignoreId),
                ],
                'is_active' => ['required', 'boolean'],
            ],

            default => abort(404),
        };

        $validated = $request->validate($rules);

        if ($category === 'districts') {
            $uniqueQuery = District::query()
                ->where('province_id', $validated['province_id'])
                ->where('name', $validated['name'])
                ->where('type', $validated['type']);

            if ($ignoreId !== null) {
                $uniqueQuery->where('id', '!=', $ignoreId);
            }

            if ($uniqueQuery->exists()) {
                throw ValidationException::withMessages([
                    'name' => 'Nama wilayah dengan jenis tersebut sudah ada di provinsi yang dipilih.',
                ]);
            }
        }

        return $validated;
    }

    private function categoryConfig(string $category): array
    {
        abort_unless(isset(self::CATEGORIES[$category]), 404);

        return self::CATEGORIES[$category];
    }

    private function findItem(string $category, int $id): object
    {
        $config = $this->categoryConfig($category);
        $model = $config['model'];

        return $model::query()->findOrFail($id);
    }

    private function activeProvinces()
    {
        return Province::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}