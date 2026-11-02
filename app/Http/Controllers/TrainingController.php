<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    /**
     * Menampilkan daftar pelatihan, pencarian, dan filter tahun.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $year = $request->input('year', '');

        // Daftar tahun yang tersedia berdasarkan tanggal mulai pelatihan.
        $years = Training::query()
            ->whereNotNull('start_date')
            ->selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        // Query utama data pelatihan.
        $trainings = Training::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('organizer', 'like', "%{$search}%")
                        ->orWhere('batch_number', 'like', "%{$search}%")
                        ->orWhere('funding_source', 'like', "%{$search}%");
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
     * Menampilkan form edit pelatihan.
     */
    public function edit(Training $training)
    {
        return view('trainings.edit', compact('training'));
    }

    /**
     * Memperbarui data pelatihan.
     */
    public function update(Request $request, Training $training)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'organizer' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
        ]);

        $training->update($validated);

        return redirect()
            ->route('trainings.index', array_filter([
                'search' => $request->query('search'),
                'year' => $request->query('year'),
            ]))
            ->with('success', 'Data pelatihan berhasil diperbarui.');
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
}