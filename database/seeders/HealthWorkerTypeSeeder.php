<?php

namespace Database\Seeders;

use App\Models\HealthWorkerType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HealthWorkerTypeSeeder extends Seeder
{
    public function run(): void
    {
        $professions = [
            'Dokter',
            'Dokter Gigi',
            'Dokter Spesialis',
            'Dokter Gigi Spesialis',
            'Bidan',
            'Perawat',
            'Tenaga Sanitasi Lingkungan',
            'Tenaga Kesehatan Masyarakat',
            'Tenaga Vokasi Kefarmasian',
            'Tenaga Gizi',
            'Tenaga Teknologi Laboratorium Medik',
            'Apoteker',
            'Psikolog Klinis',
            'Fisioterapis',
            'Terapis Okupasional',
            'Terapis Wicara',
            'Akupunktur',
            'Perekam Medis dan Informasi Kesehatan',
            'Teknisi Kardiovaskuler',
            'Teknisi Pelayanan Darah',
            'Optometris',
            'Teknisi Gigi',
            'Penata Anestesi',
            'Terapis Gigi dan Mulut',
            'Audiologis',
            'Radiografer',
            'Elektromedis',
            'Fisikawan Medik',
            'Ortotik Prostetik',
            'Tenaga Promosi Kesehatan dan Ilmu Perilaku',
            'Tenaga Kesehatan Tradisional Ramuan atau Jamu',
            'Tenaga Kesehatan Tradisional Keterampilan',
            'Tenaga Kesehatan Tradisional Interkontinental',
            'Epidemiolog Kesehatan',
            'Pembimbing Kesehatan Kerja',
            'Entomolog Kesehatan',
            'Tenaga Kesehatan Tradisional Pengobat Tradisional',
            'Tenaga Administratif dan Kebijakan Kesehatan',
            'Nutrisionis',
            'Bidan Vokasi',
            'Bidan Profesi',
        ];

        $table = (new HealthWorkerType())->getTable();

        DB::transaction(function () use ($professions, $table) {
            foreach ($professions as $profession) {
                $exists = DB::table($table)
                    ->whereRaw(
                        'LOWER(TRIM(name)) = ?',
                        [mb_strtolower(trim($profession))]
                    )
                    ->exists();

                if (!$exists) {
                    DB::table($table)->insert([
                        'name' => $profession,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });
    }
}