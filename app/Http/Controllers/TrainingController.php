<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    /**
     * Menampilkan daftar pelatihan dan pencarian.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $trainings = Training::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('organizer', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('start_date')
            ->paginate(10)
            ->withQueryString();

        return view('trainings.index', compact(
            'trainings',
            'search'
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
            ->route('trainings.index')
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