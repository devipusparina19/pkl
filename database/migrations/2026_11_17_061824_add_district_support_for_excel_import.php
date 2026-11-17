<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menyiapkan struktur database untuk Kabupaten/Kota per peserta.
 *
 * - participants.district        : Kabupaten/Kota milik peserta (dari Excel). Ditambah jika belum ada.
 * - training_histories.district  : snapshot wilayah peserta pada kegiatan tersebut. Ditambah jika belum ada.
 * - trainings.district           : dijadikan nullable, karena satu pelatihan kini bisa berisi
 *                                  peserta dari banyak Kabupaten/Kota.
 *
 * Catatan: pada Laravel 10 ke bawah, ->change() membutuhkan paket doctrine/dbal
 * (composer require doctrine/dbal). Laravel 11+ tidak membutuhkannya.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('participants') && !Schema::hasColumn('participants', 'district')) {
            Schema::table('participants', function (Blueprint $table) {
                $table->string('district')->nullable()->index();
            });
        }

        if (Schema::hasTable('training_histories') && !Schema::hasColumn('training_histories', 'district')) {
            Schema::table('training_histories', function (Blueprint $table) {
                $table->string('district')->nullable()->index();
            });
        }

        if (Schema::hasTable('trainings') && Schema::hasColumn('trainings', 'district')) {
            Schema::table('trainings', function (Blueprint $table) {
                $table->string('district')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('training_histories') && Schema::hasColumn('training_histories', 'district')) {
            Schema::table('training_histories', function (Blueprint $table) {
                $table->dropIndex(['district']);
                $table->dropColumn('district');
            });
        }

        // participants.district dan trainings.district sengaja tidak diubah saat rollback
        // karena kolom tersebut sudah ada dan dipakai sebelum migration ini.
    }
};