<?php

namespace App\Http\Controllers;

use App\Imports\TrainingImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

class ImportController extends Controller
{
    public function index()
    {
        return view('imports.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:10240',
            ],
            'angkatan' => [
                'required',
                'string',
                'max:50',
            ],
            'sumber_dana' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $spreadsheet = null;

        try {
            $file = $request->file('file');

            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();

            $trainingName = trim(
                (string) $sheet->getCell('A2')->getValue()
            );

            $dateRange = trim(
                (string) $sheet->getCell('A5')->getFormattedValue()
            );

            if ($trainingName === '') {
                return back()
                    ->withInput()
                    ->withErrors([
                        'file' => 'Nama pelatihan tidak ditemukan pada sel A2 Excel.',
                    ]);
            }

            if ($dateRange === '') {
                return back()
                    ->withInput()
                    ->withErrors([
                        'file' => 'Rentang tanggal pelatihan tidak ditemukan pada sel A5 Excel.',
                    ]);
            }

            $dates = preg_split('/\s*[-–—]\s*/u', $dateRange);

            if (! is_array($dates) || count($dates) !== 2) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'file' => 'Format tanggal pada sel A5 harus berupa tanggal mulai - tanggal selesai.',
                    ]);
            }

            try {
                $startDate = $this->parseIndonesianDate($dates[0]);
                $endDate = $this->parseIndonesianDate($dates[1]);
            } catch (Throwable $e) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'file' => 'Tanggal pada sel A5 tidak valid. Gunakan format seperti 18 MEI 2026 - 23 MEI 2026.',
                    ]);
            }

            if ($endDate->lt($startDate)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'file' => 'Tanggal selesai tidak boleh lebih awal daripada tanggal mulai.',
                    ]);
            }

            $import = new TrainingImport(
                batchNumber: $validated['angkatan'],
                fundingSource: $validated['sumber_dana'],
            );

            Excel::import($import, $file);

            return redirect()
                ->route('imports.index')
                ->with('import_summary', [
                    'processed' => $import->processed,
                    'participants' => $import->createdParticipants,
                    'trainings' => $import->createdTrainings,
                    'histories' => $import->createdHistories,
                    'skipped' => $import->skipped,
                    'errors' => $import->errors,
                ]);
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'file' => 'Impor gagal diproses. Periksa format Excel dan log Laravel untuk detailnya.',
                ]);
        } finally {
            if ($spreadsheet !== null) {
                $spreadsheet->disconnectWorksheets();
            }
        }
    }

    private function parseIndonesianDate(string $value): Carbon
    {
        $value = mb_strtolower(trim($value));

        $months = [
            'januari' => 'january',
            'februari' => 'february',
            'maret' => 'march',
            'april' => 'april',
            'mei' => 'may',
            'juni' => 'june',
            'juli' => 'july',
            'agustus' => 'august',
            'september' => 'september',
            'oktober' => 'october',
            'november' => 'november',
            'desember' => 'december',
        ];

        $value = strtr($value, $months);

        return Carbon::parse($value);
    }
}