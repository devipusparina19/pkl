<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * 1. Master provinsi.
         */
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        /*
         * 2. Master kabupaten/kota/mitra.
         * Setiap entri terhubung ke satu provinsi.
         */
        Schema::create('districts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('province_id')
                ->constrained('provinces')
                ->restrictOnDelete();

            $table->string('name');

            $table->enum('type', [
                'kabupaten',
                'kota',
                'mitra',
            ])->default('kabupaten');

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(
                ['province_id', 'name', 'type'],
                'districts_province_name_type_unique'
            );
        });

        /*
         * 3. Master sumber dana.
         */
        Schema::create('funding_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        /*
         * 4. Master tahun untuk pilihan filter.
         */
        Schema::create('training_years', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        /*
         * 5. Master jenis tenaga kesehatan.
         */
        Schema::create('health_worker_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        /*
         * Hanya menghapus tabel yang dibuat oleh migration ini.
         * Tidak menghapus tabel peserta, pelatihan, atau riwayat.
         */
        Schema::dropIfExists('health_worker_types');
        Schema::dropIfExists('training_years');
        Schema::dropIfExists('funding_sources');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('provinces');
    }
};